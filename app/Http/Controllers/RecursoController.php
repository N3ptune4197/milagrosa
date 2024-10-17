<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RecursoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Marca;
use App\Models\Categoria;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Notifications\LoanDueNotification;
use Illuminate\Support\Facades\DB;
use App\Models\Prestamo;

class RecursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Query base de los recursos con relaciones
        $query = Recurso::with('categoria', 'marca');

        // Filtrar por número de serie
        if ($request->filled('serial_number')) {
            $query->where('nro_serie', 'like', '%' . $request->serial_number . '%');
        }

        // Filtrar por categoría
        if ($request->filled('categoria_id')) {
            $query->where('id_categoria', $request->categoria_id);
        }

        // Filtrar por marca
        if ($request->filled('marca_id')) {
            $query->where('id_marca', $request->marca_id);
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtrar por fecha de registro
        if ($request->filled('fecha_registro')) {
            $query->whereDate('fecha_registro', $request->fecha_registro);
        }

        // Paginación después de aplicar los filtros
        $recursos = $query->paginate();

        $categorias = Categoria::select('id', 'nombre')->get();
        $marcas = Marca::select('id', 'nombre')->get();

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
        return view('recurso.index', compact('loans', 'recursos', 'categorias', 'marcas', 'notificacionesHoy', 'notificacionesAtrasadas', 'totalNotificaciones'))
            ->with('i', ($request->input('page', 1) - 1) * $recursos->perPage());
    }





    /**
     * Store a newly created resource in storage.
     */

    public function store(RecursoRequest $request)
    {
        $request->validate([
            'nro_serie' => [
                'required',
                'string',
                'max:255',
                Rule::unique('recursos')->ignore($request->input('recurso_id')) // Ignorar en actualizaciones
            ],
        ]);
        try {
            $validatedData = $request->validated();
            $validatedData['estado'] = 1; // Estado por defecto 'disponible'
            Recurso::create($validatedData);

            return redirect()->back()->with('success', 'Recurso creado exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput(); // Aquí no es necesario agregar el mensaje de error genérico
        }
    }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $recurso = Recurso::find($id);

        if ($recurso) {
            return response()->json($recurso);
        } else {
            return response()->json(['message' => 'Recurso no encontrado'], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nro_serie' => [
                'required',
                'string',
                'max:255',
                Rule::unique('recursos')->ignore($id) // Ignorar el recurso actual
            ],
        ]);
        $recurso = Recurso::find($id);

        $recurso->nro_serie = $request->nro_serie;
        $recurso->id_categoria = $request->id_categoria;
        $recurso->id_marca = $request->id_marca;
        $recurso->estado = $request->estado;

        // No actualizamos 'fecha_registro', solo los campos relevantes
        $recurso->save();

        return redirect()->route('recursos.index')->with('success', 'Recurso actualizado con éxito.');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Intenta eliminar la categoría
            $recurso = Recurso::findOrFail($id);
            $recurso->delete();

            return response()->json(['message' => 'Recurso eliminado correctamente.'], 200);
        } catch (QueryException $e) {
            // Si hay un error de restricción de integridad, captura la excepción
            if ($e->getCode() == 23000) { // Código de error de violación de clave foránea
                return response()->json(['message' => 'No se puede eliminar esta categoría porque está relacionada con otros registros.'], 400);
            }

            return response()->json(['message' => 'Error al eliminar el recurso.'], 500);
        }
    }


}