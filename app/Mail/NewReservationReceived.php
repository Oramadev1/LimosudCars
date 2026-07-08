<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewReservationReceived extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Reservation $reservation) {}

    public function envelope(): Envelope
    {
        $customer = $this->reservation->customer;
        $replyTo = [];

        if ($customer?->email) {
            $replyTo[] = new Address($customer->email, $customer->full_name);
        }

        return new Envelope(
            subject: 'New reservation request — '.$this->reservation->reservation_number,
            replyTo: $replyTo,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.new-reservation-received',
            with: [
                'reservation' => $this->reservation,
            ],
        );
    }
}
