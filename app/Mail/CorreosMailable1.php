<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CorreosMailable1 extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Recuperar password QuimicApp";

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $usuario, $recuperarCONT;

    public function __construct($usuario, $recuperarCONT)
    {
        $this->usuario = $usuario;
        $this->recuperarCONT = $recuperarCONT;
        //echo $usuario;
        //exit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->recuperarCONT){
            $subject = "Recuperar password QuimicApp";
            $data = array('usuario' => $this->usuario);
            return $this->view('emails.correospw')->with($data);
        }
        else{
            $subject = "Verificación QuimicApp";
            $data = array('usuario' => $this->usuario);
            return $this->view('emails.correos')->with($data);
        }
        
        //echo $this->hola;exit;
    }
}
