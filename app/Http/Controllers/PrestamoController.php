<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Prestamo;
use App\Models\Personal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PrestamoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Detalleprestamo;
use App\Models\Recurso;
use Illuminate\Support\Facades\DB;
use App\Models\Categoria;
use Illuminate\Support\Facades\Log;
use App\Notifications\LoanDueNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Twilio\Rest\Client;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {

        $prestamoCount = DB::select('SELECT COUNT(*) as total FROM prestamos')[0]->total;

        // Inicia la consulta con los filtros
        $query = Prestamo::with('detalleprestamos.recurso');

        // Filtrar por id del personal
        if ($request->filled('personal_name')) {
            $query->whereHas('personal', function ($q) use ($request) {
                $q->where('id', $request->personal_name);
            });
        }

        // Filtrar por rango de fechas
        if ($request->filled('start_date')) {
            if ($request->filled('end_date')) {
                $query->whereBetween('fecha_prestamo', [$request->start_date, $request->end_date]);
            } else {
                // Si solo se selecciona la fecha de inicio, mostrar desde esa fecha en adelante
                $query->where('fecha_prestamo', '>=', $request->start_date);
            }
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('serial_number')) {
            $query->whereHas('detalleprestamos.recurso', function ($q) use ($request) {
                $q->where('nro_serie', 'like', '%' . $request->serial_number . '%');
            });
        }


        // Ordenar los préstamos por estado, priorizando los 'activos' primero y luego por fecha en orden descendente
        $prestamos = $query->orderByRaw("CASE WHEN estado = 'activo' THEN 1 ELSE 2 END")
            ->orderBy('fecha_prestamo', 'desc')
            ->paginate($prestamoCount);

        // Obtener el listado de personal para el dropdown
        $personals = Personal::select('id', 'nombres', 'a_paterno')->get();
        $recursos = Recurso::where('estado', 1)->get();
        $recursosDisponiblesCount = Recurso::where('estado', 1)->count();
        $categorias = Categoria::all();

        // Extraer los números de serie únicos
        $uniqueRecursos = $prestamos->pluck('detalleprestamos.*.recurso')
            ->flatten()
            ->unique('nro_serie')
            ->filter() // Eliminar posibles nulos
            ->values(); // Reindexar la colección

        $highlightId = $request->get('highlight'); // Obtener el ID a resaltar
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
        return view('prestamo.index', compact('loans', 'prestamos', 'personals', 'recursos', 'recursosDisponiblesCount', 'categorias', 'uniqueRecursos', 'highlightId', 'notificacionesHoy', 'notificacionesAtrasadas', 'totalNotificaciones'))
            ->with('i', ($request->input('page', 1) - 1) * $prestamos->perPage());
    }

    public function exportPdf(Request $request)
    {
        // Inicia la consulta con los préstamos y sus relaciones
        $query = Prestamo::with('detalleprestamos.recurso', 'personal');

        // Aplica los mismos filtros que en el método index
        if ($request->filled('personal_name')) {
            $query->whereHas('personal', function ($q) use ($request) {
                $q->where('id', $request->personal_name);
            });
        }

        if ($request->filled('start_date')) {
            if ($request->filled('end_date')) {
                $query->whereBetween('fecha_prestamo', [$request->start_date, $request->end_date]);
            } else {
                $query->where('fecha_prestamo', '>=', $request->start_date);
            }
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('serial_number')) {
            $query->whereHas('detalleprestamos.recurso', function ($q) use ($request) {
                $q->where('nro_serie', 'like', '%' . $request->serial_number . '%');
            });
        }

        // Ordenar los préstamos por estado y fecha
        $prestamos = $query->orderByRaw("CASE WHEN estado = 'activo' THEN 1 ELSE 2 END")
            ->orderBy('fecha_prestamo', 'desc')
            ->get(); // Usa get() para obtener todos los resultados sin paginación

        // Genera el PDF
        $pdf = PDF::loadView('prestamo.pdf', compact('prestamos'));

        // Descarga el archivo PDF
        return $pdf->download('Reporte de prestamos.pdf');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $prestamo = new Prestamo();
        $personals = Personal::select('id', 'nombres', 'a_paterno')->get(); // Obtener el listado de personal
        $recursos = Recurso::where('estado', 1)->get();
        return view('prestamo.create', compact('prestamo', 'personals', 'recursos'));
    }

    /**
     * Store a newly created resource in storage.
     */





     public function store(PrestamoRequest $request): RedirectResponse
    {
        // Obtener los datos del formulario
        $idPersonal = $request->input('idPersonal');
        $observacion = $request->input('observacion');
        $idRecursos = $request->input('idRecurso'); // Array de recursos seleccionados
        $fechaDevoluciones = $request->input('fecha_devolucion'); // Array de fechas de devolución

        // Validar que haya recursos seleccionados
        if (empty($idRecursos) || !is_array($idRecursos)) {
            return back()->withErrors(['error' => 'Debes seleccionar al menos un recurso.']);
        }

        // Validar que las fechas de devolución estén presentes y sean válidas
        if (empty($fechaDevoluciones) || !is_array($fechaDevoluciones) || count($fechaDevoluciones) !== count($idRecursos)) {
            return back()->withErrors(['error' => 'Debes proporcionar una fecha de devolución para cada recurso seleccionado.']);
        }

        // Validar la cantidad de recursos disponibles
        $cantidadRecursosSeleccionados = count($idRecursos);
        $cantidadRecursosDisponibles = Recurso::where('estado', 1)->count();

        if ($cantidadRecursosSeleccionados > $cantidadRecursosDisponibles) {
            return Redirect::back()->withErrors(['error' => 'No puedes seleccionar más recursos de los que están disponibles.']);
        }

        // Iniciar una transacción para asegurar la integridad de los datos
        DB::beginTransaction();

        try {
            // Recorrer los recursos seleccionados para crear los detalles del préstamo
            foreach ($idRecursos as $index => $idRecursoItem) {
                $recurso = Recurso::find($idRecursoItem);

                // Verificar que el recurso esté disponible antes de procesar
                if ($recurso && $recurso->estado == 1) {
                    // Crear un préstamo individual para cada recurso
                    $prestamo = Prestamo::create([
                        'idPersonal' => $idPersonal,
                        'fecha_prestamo' => now(), // Fecha actual del sistema
                        'observacion' => $observacion
                    ]);

                    // Crear el detalle del préstamo asociado con 'fecha_devolucion'
                    DetallePrestamo::create([
                        'idprestamo' => $prestamo->id,
                        'id_recurso' => $recurso->id,
                        'fecha_devolucion' => $fechaDevoluciones[$index],
                    ]);

                    // Actualizar el estado del recurso a "Prestado"
                    $recurso->estado = 2; // Estado 2 significa "Prestado"
                    $recurso->save();

                    
                } else {
                    // Si el recurso no está disponible, lanzar una excepción
                    throw new \Exception("El recurso con ID {$idRecursoItem} no está disponible.");
                }
            }

                // Enviar mensaje de WhatsApp con Twilio
                $personal = Personal::find($idPersonal);
                $this->sendWhatsAppNotification($personal, $prestamo);

            // Confirmar la transacción
            DB::commit();

            return Redirect::route('prestamos.index')
                ->with('success', 'Préstamo creado exitosamente.');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollback();

            return Redirect::back()->withErrors(['error' => 'Error al crear los préstamos: ' . $e->getMessage()]);
        }
    }



     private function sendWhatsAppNotification(Personal $personal, Prestamo $prestamo)
     {
         // Credenciales de Twilio
         $sid = env('TWILIO_SID');
         $token = env('TWILIO_AUTH_TOKEN');
         $from = env('TWILIO_WHATSAPP_FROM'); // Número habilitado de Twilio para WhatsApp
         $to = 'whatsapp:+51970111651'; // Número del docente (asegúrate de que esté en formato internacional)
     
         // Crea un cliente de Twilio
         $client = new Client($sid, $token);

        




         // Mensaje a enviar
         $message = "*{$personal->nombres} {$personal->a_paterno} {$personal->a_materno}* realizó un préstamo!!
                  - Fecha del préstamo: {$prestamo->fecha_prestamo->format('d/m/Y')}

         Para ver detalles del préstamo, ingresar al sistema!";
     
         // Enviar el mensaje
         $client->messages->create($to, [
             'from' => $from,
             'body' => $message,
         ]);
     }

























    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $prestamo = Prestamo::find($id);

        return view('prestamo.index', compact('prestamo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $prestamo = Prestamo::find($id);
        $personals = Personal::select('id', 'nombres', 'a_paterno')->get(); // Obtener el listado de personal

        return view('prestamo.edit', compact('prestamo', 'personals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrestamoRequest $request, Prestamo $prestamo): RedirectResponse
    {
        $validated = $request->validated();
        $validated['fecha_prestamo'] = $prestamo->fecha_prestamo; // Mantener la fecha de préstamo actual

        $prestamo->update($validated);

        return Redirect::route('prestamos.index')
            ->with('success', 'Préstamo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        Prestamo::find($id)->delete();

        return Redirect::route('prestamos.index')
            ->with('success', 'Préstamo eliminado exitosamente');
    }

    public function markAsReturned(Request $request, $id)
    {
        // Encuentra el detalle del préstamo
        $detallePrestamo = DetallePrestamo::with('prestamo', 'recurso')->find($id);

        if (!$detallePrestamo) {
            return redirect()->route('prestamos.index')->with('error', 'Detalle del préstamo no encontrado.');
        }

        // Encuentra el recurso relacionado
        $recurso = Recurso::find($detallePrestamo->id_recurso);

        if (!$recurso) {
            return redirect()->route('prestamos.index')->with('error', 'Recurso no encontrado.');
        }

        // Guarda la observación en el préstamo
        $prestamo = $detallePrestamo->prestamo;
        $prestamo->observacion = $request->input('observacion', null);
        $prestamo->save();

        // Solo actualiza la fecha_devolucion si está vacía
        if (!$detallePrestamo->fecha_devolucion) {
            $detallePrestamo->fecha_devolucion = now();
            $detallePrestamo->save();
        }

        // Cambia el estado del recurso
        $nuevoEstado = $request->input('estado');
        if ($nuevoEstado !== null) {
            $recurso->estado = $nuevoEstado != 1 ? 4 : 1;
            $recurso->save();
        }

        // Verifica si todos los detalles han sido devueltos
        if ($prestamo->detalleprestamos()->whereNull('fecha_devolucion')->count() == 0) {
            $prestamo->estado = 'desactivo';
            $prestamo->fecha_devolucion_real = now();
            $prestamo->save();
        }

        return redirect()->route('prestamos.index')->with('success', 'Recurso marcado como devuelto.');
    }




    public function obtenerPrestamosActivosCalendario()
    {

        $prestamos = Prestamo::where('estado', 'activo')
            ->with(['personal', 'detalleprestamos.recurso.categoria'])  // Asegúrate de que la relación personal esté definida en el modelo Prestamo
            ->get();

        $eventos = $prestamos->map(function ($prestamo) {
            $detalle = $prestamo->detalleprestamos->first(); // Obtener el primer detalle de préstamo
            $recurso = $detalle ? $detalle->recurso : null; // Obtener el recurso si existe en el detalle del préstamo
            $categoria = $recurso ? $recurso->categoria : null;
            return [
                'id' => $prestamo->id,
                'title' => $prestamo->personal->nombres . ' ' . $prestamo->personal->a_paterno, // Título personalizado
                'start' => $detalle->fecha_devolucion, // Fecha de inicio
                'end' => $detalle->fecha_devolucion, // Fecha de finalización
                'description' => $prestamo->observacion . ($recurso ? '' . $recurso->nro_serie : ''), // Descripción opcional
                'recurso_categoria' => $categoria->nombre,
                'fechaInicio' => Carbon::parse($prestamo->fecha_prestamo)->format('d/m/y'),
            ];
        });

        return response()->json($eventos);
    }


}
