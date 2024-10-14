<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Database\QueryException;
use App\Models\Prestamo;
use App\Notifications\LoanDueNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $categorias = Categoria::paginate();
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

            // Notificaciones para hoy y atrasadas
            foreach ($loan->detalleprestamos as $detalle) {
                if (isset($detalle->fecha_devolucion)) {
                    $fechaDevolucion = Carbon::parse($detalle->fecha_devolucion);

                    if ($fechaDevolucion->isToday()) {
                        // Si la devolución es hoy, calculamos horas y minutos restantes o atraso
                        if ($hoy->gt($fechaDevolucion)) {
                            // Si ya pasó la hora de devolución
                            $minutosAtraso = intval($fechaDevolucion->diffInMinutes($hoy));
                            $notificacionesHoy[] = (object) [
                                'id' => $detalle->id_recurso,
                                'id_recurso' => $detalle->id_recurso,
                                'categoria' => $detalle->recurso->categoria->nombre,
                                'nro_serie' => $detalle->recurso->nro_serie,
                                'a_paterno' => $user->a_paterno,
                                'minutos_atraso' => $minutosAtraso,  // Atraso en minutos
                            ];
                        } else {
                            // Si aún no ha llegado la hora, mostramos el tiempo restante
                            $horasRestantes = intval($hoy->diffInHours($fechaDevolucion));
                            $minutosRestantes = intval($hoy->diffInMinutes($fechaDevolucion) % 60);
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
                        $horasTotalesRestantes = $hoy->diffInHours($fechaDevolucion);

                        if ($horasTotalesRestantes >= 24) {
                            // Si faltan más de 24 horas, mostrar días completos
                            $diasRestantes = intval($hoy->diffInDays($fechaDevolucion));

                            $notificacionesHoy[] = (object) [
                                'id' => $detalle->id_recurso,
                                'id_recurso' => $detalle->id_recurso,
                                'categoria' => $detalle->recurso->categoria->nombre,
                                'nro_serie' => $detalle->recurso->nro_serie,
                                'a_paterno' => $user->a_paterno,
                                'dias_restantes' => $diasRestantes,
                            ];
                        } else {
                            // Si faltan menos de 24 horas, mostrar horas y minutos
                            $horasRestantes = intval($hoy->diffInHours($fechaDevolucion));
                            $minutosRestantes = intval($hoy->diffInMinutes($fechaDevolucion) % 60);

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
                        // Si la fecha de devolución ya pasó
                        $diasAtraso = intval($fechaDevolucion->diffInDays($hoy));

                        $notificacionesAtrasadas[] = (object) [
                            'id' => $detalle->id_recurso,
                            'id_recurso' => $detalle->id_recurso,
                            'categoria' => $detalle->recurso->categoria->nombre,
                            'nro_serie' => $detalle->recurso->nro_serie,
                            'a_paterno' => $user->a_paterno,
                            'fecha_devolucion' => $detalle->fecha_devolucion,
                            'dias_atraso' => $diasAtraso, // Días de atraso sin decimales
                        ];
                    }
                }
            }
        }

        // Contar total de notificaciones
        $totalNotificaciones = count($notificacionesHoy) + count($notificacionesAtrasadas);

        // Pasar las notificaciones a la vista
        return view('categoria.index', compact('loans', 'categorias', 'notificacionesHoy', 'notificacionesAtrasadas', 'totalNotificaciones'))
            ->with('i', ($request->input('page', 1) - 1) * $categorias->perPage());
    }




    /**
     * Store a newly created resource in storage.
     */



    public function store(CategoriaRequest $request): RedirectResponse
    {
        // Validación y creación de la categoría
        Categoria::create($request->validated());

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Categoría creada exitosamente.');
    }









    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Obtener la categoría por su ID
        $categoria = Categoria::findOrFail($id);

        // Devolver los datos de la categoría en formato JSON para usar con AJAX
        return response()->json($categoria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriaRequest $request, $id): RedirectResponse
    {
        // Encontrar la categoría por su ID
        $categoria = Categoria::findOrFail($id);

        // Actualizar los datos de la categoría
        $categoria->update($request->validated());

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */


    public function destroy($id)
    {
        try {
            // Intenta eliminar la categoría
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();

            return response()->json(['message' => 'Categoría eliminada correctamente.'], 200);
        } catch (QueryException $e) {
            // Si hay un error de restricción de integridad, captura la excepción
            if ($e->getCode() == 23000) { // Código de error de violación de clave foránea
                return response()->json(['message' => 'No se puede eliminar esta categoría porque está relacionada con otros registros.'], 400);
            }

            return response()->json(['message' => 'Error al eliminar la categoría.'], 500);
        }
    }

}