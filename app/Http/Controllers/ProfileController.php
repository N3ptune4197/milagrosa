<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Prestamo;
use App\Notifications\LoanDueNotification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Mostrar la página del perfil
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

        return view('profile.Profile', compact('notificacionesHoy', 'notificacionesAtrasadas', 'totalNotificaciones'));
    }

    // Mostrar la vista de edición del perfil
    public function edit()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Pasar los datos del usuario a la vista
        return view('profile.edit', compact('user'));
    }

    // Actualizar la información del perfil
    public function update(Request $request)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Validar los datos
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id, // Ignorar email del usuario actual
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed', // Confirmación de contraseña
        ]);

        // Actualizar nombre de usuario
        $user->name = $request->input('username');

        // Actualizar email
        $user->email = $request->input('email');

        // Actualizar número de teléfono
        $user->phone = $request->input('phone');

        // Actualizar contraseña solo si se proporciona
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Guardar cambios
        $user->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('profile.index')->with('success', 'Perfil actualizado correctamente.');
    }
}
