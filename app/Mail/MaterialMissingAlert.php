<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MaterialMissingAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $materialName;
    public $user;

    public function __construct($materialName, $user)
    {
        $this->materialName = $materialName;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Material Request: ' . $this->materialName)
                    ->view('emails.material_missing');
    }
}
