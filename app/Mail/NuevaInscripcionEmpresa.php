<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NuevaInscripcionEmpresa extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $oferta;

    public function __construct($usuario, $oferta)
    {
        $this->usuario = $usuario;
        $this->oferta = $oferta;
    }

    public function build()
    {
        return $this->view('emails.empresas.nuevaInscripcion')
                    ->subject('¡Nueva inscripción: ' . $this->oferta->titulo . '!');
    }
}