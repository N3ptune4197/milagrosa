<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LoanDueNotification extends Notification
{
    use Queueable;

    protected $loan;

    public function __construct($loan)
    {
        $this->loan = $loan;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'El préstamo del recurso ' . $this->loan->nro_serie . ' de la categoría ' . $this->loan->categoria . ' vence el ' . $this->loan->fecha_devolucion . '.',
            'loan_id' => $this->loan->id,
            'due_date' => $this->loan->fecha_devolucion,
        ];
    }
}

