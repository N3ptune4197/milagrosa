<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LoanDueNotification;
use App\Models\Loan;
use App\Models\User;
use App\Models\Prestamo;
use Illuminate\Notifications\DatabaseNotification;
class HomeController extends Controller
{

    public function index()
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

        // Pasar las notificaciones a la vista
        return view('home', compact('loans', 'notificacionesHoy', 'notificacionesAtrasadas', 'totalNotificaciones'));
    }


    public function deleteNotification($id)
    {
        // Usar el modelo de notificaciones por defecto
        $notificacion = DatabaseNotification::find($id);

        if ($notificacion) {
            $notificacion->delete(); // Elimina la notificación
            return response()->json(['success' => true, 'message' => 'Notificación eliminada correctamente.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Notificación no encontrada.']);
        }
    }


}

