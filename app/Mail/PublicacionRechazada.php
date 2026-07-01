<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PublicacionRechazada extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $mail = $this->subject('Tu publicación ha sido rechazada')
                     ->view('emails.publicacion-rechazada')
                     ->with('data', $this->data);

        // Adjuntar motivo como archivo de texto si está disponible
        if (!empty($this->data['motivo']) && is_string($this->data['motivo'])) {
            $mail->attachData($this->data['motivo'], 'motivo_rechazo.txt', [
                'mime' => 'text/plain',
            ]);
        }

        // Adjuntar archivo externo si se proporciona ruta
        if (!empty($this->data['attachment_path'])) {
            try {
                $mail->attach($this->data['attachment_path']);
            } catch (\Exception $e) {
                // Ignorar fallo de adjunto
            }
        }

        return $mail;
    }
}
