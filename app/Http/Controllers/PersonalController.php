<?php

namespace App\Http\Controllers;
use App\Models\Prestamo;
use Illuminate\Support\Facades\DB;
use App\Models\Personal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PersonalRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Notifications\LoanDueNotification;
class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
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
        $personals = Personal::paginate();
        // Pasar las notificaciones a la vista
        return view('personal.index', compact('loans', 'personals', 'notificacionesHoy', 'notificacionesAtrasadas', 'totalNotificaciones'))
            ->with('i', ($request->input('page', 1) - 1) * $personals->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */



    /* public function create(): View
    {
        $personal = new Personal();

        return view('personal.create', compact('personal'));
    }
 */

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonalRequest $request): RedirectResponse
    {
        // Validación
        $request->validate([
            'nro_documento' => [
                'required',
                'string',
                'max:255',
                Rule::unique('personals')->ignore($request->input('personal_id')) // Ignorar en actualizaciones
            ],
        ]);

        // Verifica si el número de documento ya existe
        if (Personal::where('nro_documento', $request->nro_documento)->exists()) {
            return redirect()->back()->with('error', 'El documento ya existe. Por favor, ingrese uno nuevo.');
        }

        // Crear el nuevo personal
        Personal::create($request->validated());

        return redirect()->back()->with('success', 'Personal creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */


    /* public function show($id): View
   {
       $personal = Personal::find($id);

       return view('personal.show', compact('personal'));
   } */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $personal = Personal::findOrFail($id);
        return response()->json($personal);
    }

    /**
     * Update the specified resource in storage.
     */

    //  public function update(PersonalRequest $request, Personal $personal): RedirectResponse
    public function update(PersonalRequest $request, $id): RedirectResponse
    {
        // Validación
        $request->validate([
            'nro_documento' => [
                'required',
                'string',
                'max:255',
                Rule::unique('personals')->ignore($id) // Ignorar el personal actual
            ],
        ]);
        // Encontrar la categoría por su ID
        $personal = Personal::findOrFail($id);

        // Actualizar los datos de la categoría
        $personal->update($request->validated());

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Personal actualizada exitosamente.');
    }


    public function destroy($id)
    {
        try {
            // Intenta eliminar el personal
            $personal = Personal::findOrFail($id);
            $personal->delete();

            return response()->json(['message' => 'Personal eliminado correctamente.'], 200);
        } catch (QueryException $e) {
            // Si hay un error de restricción de integridad, captura la excepción
            if ($e->getCode() == 23000) { // Código de error de violación de clave foránea
                return response()->json(['message' => 'No se puede eliminar este Personal porque está relacionada con otros registros.'], 400);
            }

            return response()->json(['message' => 'Error al eliminar el Personal.'], 500);
        }
    }












    public function buscarDni($dni)
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImNhcmxvc2NoZXJvMTM0QGdtYWlsLmNvbSJ9.R_AH9D_aSHmHUK7K8O5WnLFzjt4NnpTRaZgybVhlZgg';
        $url = "https://dniruc.apisperu.com/api/v1/dni/{$dni}?token={$token}";

        $response = Http::get($url);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['success' => false], 404);
    }



}







