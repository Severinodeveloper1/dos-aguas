<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmittedUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public ContactSubmission $submission;

    public function __construct(ContactSubmission $submission)
    {
        $this->submission = $submission;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibimos tu mensaje - Dos Aguas',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-user',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

