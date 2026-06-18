<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    /**
     * Create a payment and recalculate the reservation payment status.
     *
     * @param  array<string, mixed>  $data
     */
    public function createPayment(array $data, ?User $createdBy = null): Payment
    {
        return DB::transaction(function () use ($data, $createdBy): Payment {
            $paymentData = $this->preparePaymentData($data);
            $reservation = Reservation::whereKey($paymentData['reservation_id'])->lockForUpdate()->firstOrFail();

            $this->ensurePaymentAllowed($reservation);

            $payment = Payment::create([
                ...$paymentData,
                'created_by' => $createdBy?->id,
            ]);

            $this->recalculateReservationPaymentStatus($reservation);

            return $payment;
        });
    }

    /**
     * Update a payment and recalculate affected reservation payment statuses.
     *
     * @param  array<string, mixed>  $data
     */
    public function updatePayment(Payment $payment, array $data): Payment
    {
        return DB::transaction(function () use ($payment, $data): Payment {
            $payment = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
            $oldReservationId = $payment->reservation_id;
            $paymentData = $this->preparePaymentData($data, false);

            if (array_key_exists('reservation_id', $paymentData)) {
                Reservation::whereKey($paymentData['reservation_id'])->lockForUpdate()->firstOrFail();
            }

            $payment->update($paymentData);

            $this->recalculateReservationPaymentStatus(Reservation::whereKey($oldReservationId)->lockForUpdate()->firstOrFail());

            if ($payment->reservation_id !== $oldReservationId) {
                $this->recalculateReservationPaymentStatus($payment->reservation()->lockForUpdate()->firstOrFail());
            }

            return $payment;
        });
    }

    /**
     * Safely cancel a payment without deleting the financial record.
     */
    public function cancelPayment(Payment $payment): Payment
    {
        return DB::transaction(function () use ($payment): Payment {
            $payment = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
            $reservation = Reservation::whereKey($payment->reservation_id)->lockForUpdate()->firstOrFail();

            $payment->update([
                'payment_status_id' => $this->paymentStatusId('cancelled'),
            ]);

            $this->recalculateReservationPaymentStatus($reservation);

            return $payment;
        });
    }

    /**
     * Recalculate a reservation payment status from paid payment totals.
     */
    public function recalculateReservationPaymentStatus(Reservation $reservation): Reservation
    {
        $paidStatusId = $this->paymentStatusId('paid');

        $paidAmount = (float) $reservation->payments()
            ->where('payment_status_id', $paidStatusId)
            ->sum('amount');

        $targetStatusSlug = match (true) {
            $paidAmount <= 0 => 'unpaid',
            $paidAmount < (float) $reservation->total_price => 'partial_paid',
            default => 'paid',
        };

        $reservation->update([
            'payment_status_id' => $this->paymentStatusId($targetStatusSlug),
        ]);

        return $reservation->refresh();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function preparePaymentData(array $data, bool $requireReservation = true): array
    {
        $prepared = [];

        if ($requireReservation || array_key_exists('reservation_id', $data)) {
            $prepared['reservation_id'] = $data['reservation_id'];
        }

        if (array_key_exists('payment_method_slug', $data)) {
            $prepared['payment_method_id'] = PaymentMethod::where('slug', $data['payment_method_slug'])->firstOrFail()->id;
        }

        if (array_key_exists('payment_type_slug', $data)) {
            $prepared['payment_type_id'] = PaymentType::where('slug', $data['payment_type_slug'])->firstOrFail()->id;
        }

        if (array_key_exists('payment_status_slug', $data)) {
            $prepared['payment_status_id'] = $this->paymentStatusId($data['payment_status_slug']);
        }

        foreach (['amount', 'payment_date', 'paid_by_customer_name', 'reference', 'notes'] as $field) {
            if (array_key_exists($field, $data)) {
                $prepared[$field] = $data[$field];
            }
        }

        return $prepared;
    }

    private function paymentStatusId(string $slug): int
    {
        return PaymentStatus::where('slug', $slug)->firstOrFail()->id;
    }

    /**
     * @throws ValidationException
     */
    private function ensurePaymentAllowed(Reservation $reservation): void
    {
        $reservation->loadMissing('status');

        if (in_array($reservation->status?->slug, ['cancelled', 'rejected'], true)) {
            throw ValidationException::withMessages([
                'reservation_id' => 'Payments cannot be recorded for cancelled or rejected reservations.',
            ]);
        }
    }
}
