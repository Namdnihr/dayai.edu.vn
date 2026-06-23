<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionalAutomationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public string $mailTitle,
        public string $mailBody,
        public array $mailMetadata = [],
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailTitle,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.automation.transactional',
            text: 'emails.automation.transactional-text',
        );
    }
}
