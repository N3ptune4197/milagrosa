<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\PrestamoController;

class NotificarPrestamosPorVencerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prestamos:notificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica a los docentes sobre préstamos por vencer o vencidos.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Crear una instancia del controlador de préstamos
        $prestamoController = new PrestamoController();
        
        // Llamar a la función que notifica a los docentes
        $prestamoController->notificarPrestamosPorVencer();
        
        $this->info('Se enviaron las notificaciones de préstamos por vencer.');
    }
}
