<?php

namespace App\Mail;

use App\Support\WebsiteContactData;
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

    public function __construct(public WebsiteContactData $contact) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New website contact — '.$this->contact->name,
            replyTo: [
                new Address($this->contact->email, $this->contact->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.website-contact-received',
            with: [
                'contact' => $this->contact,
            ],
        );
    }
}
