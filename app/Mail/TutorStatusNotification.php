<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TutorStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $status;
    public $reason;

    public function __construct($user, $status, $reason = null)
    {
        $this->user = $user;
        $this->status = $status;
        $this->reason = $reason;
    }

    public function build()
    {
        $subject = $this->status === 'approved' 
            ? 'Solicitud de Registro Aprobada - Colegio Bruning' 
            : 'Solicitud de Registro Rechazada - Colegio Bruning';

        return $this->view('emails.tutor-status')
                    ->subject($subject);
    }
}   