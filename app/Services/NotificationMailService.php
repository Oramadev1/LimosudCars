<?php

namespace App\Services;

use App\Mail\NewReservationReceived;
use App\Mail\ReservationConfirmationToCustomer;
use App\Mail\WebsiteContactReceived;
use App\Models\Reservation;
use App\Support\WebsiteContactData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NotificationMailService
{
    public function sendWebsiteContactReceived(WebsiteContactData $contact): void
    {
        if (! config('limosud.notifications.send_contact_messages')) {
            return;
        }

        $this->sendToInbox(new WebsiteContactReceived($contact));
    }

    public function sendNewReservationReceived(Reservation $reservation): void
    {
        if (! config('limosud.notifications.send_reservations')) {
            return;
        }

        $reservation->loadMissing([
            'customer',
            'vehicle',
            'pickupLocation',
            'dropoffLocation',
        ]);

        $this->sendToInbox(new NewReservationReceived($reservation));
        $this->sendReservationConfirmationToCustomer($reservation);
    }

    public function sendReservationConfirmationToCustomer(Reservation $reservation): void
    {
        if (! config('limosud.notifications.send_reservations')) {
            return;
        }

        $reservation->loadMissing([
            'customer',
            'vehicle',
            'pickupLocation',
            'dropoffLocation',
        ]);

        $email = trim((string) ($reservation->customer?->email ?? ''));

        if ($email === '') {
            return;
        }

        if (config('mail.default') === 'log') {
            Log::info('Skipping customer reservation confirmation because MAIL_MAILER=log.', [
                'recipient' => $email,
                'reservation' => $reservation->reservation_number,
            ]);

            return;
        }

        try {
            Mail::to($email)->send(new ReservationConfirmationToCustomer($reservation));
        } catch (Throwable $exception) {
            Log::error('Failed to send customer reservation confirmation email.', [
                'recipient' => $email,
                'reservation' => $reservation->reservation_number,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function sendToInbox(object $mailable): void
    {
        $recipient = trim((string) config('limosud.notifications.email'));

        if ($recipient === '') {
            return;
        }

        if (config('mail.default') === 'log') {
            Log::info('Skipping notification email because MAIL_MAILER=log.', [
                'recipient' => $recipient,
                'mailable' => $mailable::class,
            ]);

            return;
        }

        try {
            Mail::to($recipient)->send($mailable);
        } catch (Throwable $exception) {
            Log::error('Failed to send notification email.', [
                'recipient' => $recipient,
                'mailable' => $mailable::class,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
