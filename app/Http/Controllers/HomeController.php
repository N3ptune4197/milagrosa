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
        $hoy = Carbon::today(); // Obtiene la fecha actual

        // Obtener préstamos activos con detalles cuyo recurso está por vencer o ya venció
        $loans = Prestamo::where('estado', 'activo')
            ->whereHas('detalleprestamos', function ($query) use ($hoy) {
                $query->whereDate('fecha_devolucion', '=', $hoy)
                    ->orWhere('fecha_devolucion', '<', $hoy);
            })
            ->with([
                'detalleprestamos' => function ($query) {
                    $query->select('idprestamo', 'fecha_devolucion', 'id_recurso');
                },
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
                $fechaDevolucion = Carbon::parse($detalle->fecha_devolucion); // Convierte la fecha de devolución a Carbon

                if ($fechaDevolucion->isSameDay($hoy)) {
                    $notificacionesHoy[] = (object) [
                        'id' => $detalle->id_recurso,
                        'id_recurso' => $detalle->id_recurso,
                        'categoria' => $detalle->recurso->categoria->nombre,
                        'nro_serie' => $detalle->recurso->nro_serie,
                        'a_paterno' => $user->a_paterno,
                        'fecha_devolucion' => $detalle->fecha_devolucion,
                    ];
                }

                if ($fechaDevolucion->lt($hoy)) { // Si la fecha de devolución es menor que hoy
                    $diasAtraso = $fechaDevolucion->diffInDays($hoy); // Calcula la diferencia en días

                    $notificacionesAtrasadas[] = (object) [
                        'id' => $detalle->id_recurso,
                        'id_recurso' => $detalle->id_recurso,
                        'categoria' => $detalle->recurso->categoria->nombre,
                        'nro_serie' => $detalle->recurso->nro_serie,
                        'a_paterno' => $user->a_paterno,
                        'fecha_devolucion' => $detalle->fecha_devolucion,
                        'dias_atraso' => $diasAtraso // Número de días que lleva atrasado
                    ];
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

