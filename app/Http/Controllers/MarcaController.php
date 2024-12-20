<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MarcaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Database\QueryException;
use App\Models\Prestamo;
use App\Notifications\LoanDueNotification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $marcas = Marca::paginate();

        $hoy = Carbon::now(); // Obtiene la fecha y hora actual

        // Obtener préstamos activos con detalles cuyo recurso está por vencer o ya venció
        $loans = Prestamo::where('estado', 'activo')
            ->whereHas('detalleprestamos', function ($query) use ($hoy) {
                $query->whereDate('fecha_devolucion', '=', $hoy->toDateString())
                    ->orWhere('fecha_devolucion', '<', $hoy);
            })
            ->with([
                'detalleprestamos.recurso.categoria',
                'personal'
            ])
            ->get();

        // Inicializar arrays para las notificaciones
        $notificacionesHoy = [];
        $notificacionesAtrasadas = [];

        foreach ($loans as $loan) {
            $user = $loan->personal;

            // Verifica si ya existe una notificación en la base de datos
            $existingNotification = DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->where('type', LoanDueNotification::class)
                ->where('data->loan_id', $loan->id)
                ->first();

            if (!$existingNotification) {
                // Si no existe, crea la notificación
                $user->notify(new LoanDueNotification($loan));
            }

            foreach ($loan->detalleprestamos as $detalle) {
                if (isset($detalle->fecha_devolucion)) {
                    $fechaDevolucion = Carbon::parse($detalle->fecha_devolucion);
                    $hoy = Carbon::now();

                    if ($fechaDevolucion->isToday()) {
                        // Si la devolución es hoy
                        if ($hoy->gt($fechaDevolucion)) {
                            // Ya pasó la hora de devolución hoy (atraso)
                            $minutosAtraso = $fechaDevolucion->diffInMinutes($hoy); // Diferencia correcta sin negativos
                            $horasAtraso = floor($minutosAtraso / 60);
                            $minutosAtraso = $minutosAtraso % 60;

                            $notificacionesHoy[] = (object) [
                                'id' => $detalle->id_recurso,
                                'id_recurso' => $detalle->id_recurso,
                                'categoria' => $detalle->recurso->categoria->nombre,
                                'nro_serie' => $detalle->recurso->nro_serie,
                                'a_paterno' => $user->a_paterno,
                                'horas_atraso' => $horasAtraso,
                                'minutos_atraso' => $minutosAtraso,
                            ];
                        } else {
                            // Tiempo restante hoy
                            $minutosRestantes = $hoy->diffInMinutes($fechaDevolucion);
                            $horasRestantes = floor($minutosRestantes / 60);
                            $minutosRestantes = $minutosRestantes % 60;

                            $notificacionesHoy[] = (object) [
                                'id' => $detalle->id_recurso,
                                'id_recurso' => $detalle->id_recurso,
                                'categoria' => $detalle->recurso->categoria->nombre,
                                'nro_serie' => $detalle->recurso->nro_serie,
                                'a_paterno' => $user->a_paterno,
                                'horas_restantes' => $horasRestantes,
                                'minutos_restantes' => $minutosRestantes,
                            ];
                        }
                    } elseif ($fechaDevolucion->gt($hoy)) {
                        // Si la devolución es en el futuro
                        $diasRestantes = floor($hoy->diffInDays($fechaDevolucion));
                        $horasRestantes = floor($hoy->diffInHours($fechaDevolucion) % 24);

                        if ($diasRestantes > 0) {
                            $notificacionesHoy[] = (object) [
                                'id' => $detalle->id_recurso,
                                'id_recurso' => $detalle->id_recurso,
                                'categoria' => $detalle->recurso->categoria->nombre,
                                'nro_serie' => $detalle->recurso->nro_serie,
                                'a_paterno' => $user->a_paterno,
                                'dias_restantes' => $diasRestantes,
                                'horas_restantes' => $horasRestantes,
                            ];
                        } else {
                            // Si faltan menos de 24 horas
                            $minutosRestantes = floor($hoy->diffInMinutes($fechaDevolucion) % 60);

                            $notificacionesHoy[] = (object) [
                                'id' => $detalle->id_recurso,
                                'id_recurso' => $detalle->id_recurso,
                                'categoria' => $detalle->recurso->categoria->nombre,
                                'nro_serie' => $detalle->recurso->nro_serie,
                                'a_paterno' => $user->a_paterno,
                                'horas_restantes' => $horasRestantes,
                                'minutos_restantes' => $minutosRestantes,
                            ];
                        }
                    } elseif ($fechaDevolucion->lt($hoy)) {
                        // Si la fecha de devolución ya pasó (atraso)
                        $diasAtraso = floor($fechaDevolucion->diffInDays($hoy));

                        $notificacionesAtrasadas[] = (object) [
                            'id' => $detalle->id_recurso,
                            'id_recurso' => $detalle->id_recurso,
                            'categoria' => $detalle->recurso->categoria->nombre,
                            'nro_serie' => $detalle->recurso->nro_serie,
                            'a_paterno' => $user->a_paterno,
                            'fecha_devolucion' => $detalle->fecha_devolucion,
                            'dias_atraso' => $diasAtraso,
                        ];
                    }
                }
            }
        }


        // Contar total de notificaciones
        $totalNotificaciones = count($notificacionesHoy) + count($notificacionesAtrasadas);

        // Pasar las notificaciones a la vista
        return view('marca.index', compact('loans', 'marcas', 'notificacionesHoy', 'notificacionesAtrasadas', 'totalNotificaciones'))
            ->with('i', ($request->input('page', 1) - 1) * $marcas->perPage());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MarcaRequest $request): RedirectResponse
    {
        Marca::create($request->validated());
        return redirect()->back()->with('success', 'Marca creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $marca = Marca::findOrFail($id);
        return response()->json($marca);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MarcaRequest $request, $id): RedirectResponse
    {
        $marca = Marca::findOrFail($id);
        $marca->update($request->validated());
        return redirect()->back()->with('success', 'Marca actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $marca = Marca::findOrFail($id);
            $marca->delete();
            return response()->json(['message' => 'Marca eliminada correctamente.'], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === 23000) {
                return response()->json(['message' => 'No se puede eliminar esta marca porque está relacionada con otros registros.'], 400);
            }
            return response()->json(['message' => 'Error al eliminar la marca.'], 500);
        }
    }
}
