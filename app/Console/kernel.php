<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define los comandos de Artisan para la aplicación.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define las tareas programadas.
     */
    protected function schedule(Schedule $schedule)
    {
        // Aquí agregas la programación de tareas
        $schedule->command('prestamos:notificar')->everyTenMinutes();
    }
}
