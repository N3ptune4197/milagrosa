<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestamo;
use App\Notifications\LoanDueNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarioController extends Controller
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

            foreach ($loan->detalleprestamos as $detalle) {
                if (isset($detalle->fecha_devolucion)) {
                    $fechaDevolucion = Carbon::parse($detalle->fecha_devolucion);
                    if ($fechaDevolucion->isToday()) {
                        // Procesar notificaciones del mismo día
                        $minutosDiferencia = $fechaDevolucion->diffInMinutes($hoy);
                        $horasDiferencia = floor($minutosDiferencia / 60);
                        $minutosDiferencia = $minutosDiferencia % 60;

                        $notificacionesHoy[] = (object) [
                            'id' => $detalle->id_recurso,
                            'id_recurso' => $detalle->id_recurso,
                            'categoria' => $detalle->recurso->categoria->nombre,
                            'nro_serie' => $detalle->recurso->nro_serie,
                            'a_paterno' => $user->a_paterno,
                            'horas_diferencia' => $horasDiferencia,
                            'minutos_diferencia' => $minutosDiferencia,
                        ];
                    } elseif ($fechaDevolucion->lt($hoy)) {
                        // Notificaciones de préstamos atrasados
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

        // Retornar la vista del calendario con las notificaciones
        return view('calendario', compact('notificacionesHoy', 'notificacionesAtrasadas', 'totalNotificaciones'));
    }
}
