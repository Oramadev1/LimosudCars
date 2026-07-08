<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebsiteContactReceived extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New website contact — '.$this->contactMessage->name,
            replyTo: [
                new Address($this->contactMessage->email, $this->contactMessage->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.website-contact-received',
            with: [
                'contactMessage' => $this->contactMessage,
            ],
        );
    }
}
