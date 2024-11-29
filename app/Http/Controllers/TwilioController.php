<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Database\QueryException;
require_once '/path/to/vendor/autoload.php';

class TwilioController extends Controller
{
    
    public function enviarMensajeWhatsApp($telefono, $mensaje) {
        // Credenciales de Twilio desde el archivo .env
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token);
    
        try {
            // Enviar mensaje
            $message = $twilio->messages->create(
                "whatsapp:+51$telefono",
                [
                    "from" => env('TWILIO_WHATSAPP_FROM'), // Número de Twilio
                    "body" => $mensaje // Mensaje que se enviará
                ]
            );
            return "Mensaje enviado con éxito.";
        } catch (\Exception $e) {
            return "Error al enviar el mensaje: " . $e->getMessage();
        }
    }
}
