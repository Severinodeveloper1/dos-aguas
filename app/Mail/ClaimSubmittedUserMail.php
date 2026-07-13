<?php

namespace App\Mail;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClaimSubmittedUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public Claim $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Reclamación ' . $this->claim->claim_code . ' - Dos Aguas',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.claim-user',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

