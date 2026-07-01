<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmpresaPublicacionAprobada extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $mail = $this->subject('Tu publicación ha sido aprobada')
                     ->view('emails.empresa-publicacion-aprobada')
                     ->with('data', $this->data);

        if (!empty($this->data['documento_aprobacion_pdf'])) {
            $mail->attach(public_path($this->data['documento_aprobacion_pdf']));
        }

        return $mail;
    }
}
