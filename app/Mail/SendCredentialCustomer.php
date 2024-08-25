<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCredentialCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public $credential;

    public function __construct($credential)
    {
        $this->credential = $credential;
    }

    public function build()
    {
        return $this->subject('Akses Login Dashboard')
            ->from('admin@example.com', 'admin')
            ->with('data', $this->credential)
            ->view('emails.sendCredential');
    }
}
