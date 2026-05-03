<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EstadoCandidaturaMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $oferta;
    public $nuevoEstado;

    public function __construct($usuario, $oferta, $nuevoEstado)
    {
        $this->usuario = $usuario;
        $this->oferta = $oferta;
        $this->nuevoEstado = $nuevoEstado;
    }

    public function build()
    {
        return $this->view('emails.candidatos.actualizacionOferta')
                    ->subject('Cambio de estado en tu candidatura');
    }
}
