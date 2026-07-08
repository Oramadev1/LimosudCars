<?php

namespace App\Services;

use App\Mail\NewReservationReceived;
use App\Mail\WebsiteContactReceived;
use App\Models\ContactMessage;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class NotificationMailService
{
    public function sendWebsiteContactReceived(ContactMessage $contactMessage): void
    {
        if (! config('limosud.notifications.send_contact_messages')) {
            return;
        }

        $this->sendToInbox(new WebsiteContactReceived($contactMessage));
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
