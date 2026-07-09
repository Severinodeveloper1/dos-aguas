<?php

namespace App\Mail;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClaimSubmittedAdminMail extends Mailable
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
            subject: 'ALERTA: Nuevo reclamo registrado ' . $this->claim->claim_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.claim-admin',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
