@php
    $customer = $reservation->customer;
    $vehicle = $reservation->vehicle;
@endphp

<x-mail::message>
# Reservation received

Hello {{ $customer?->full_name ?? 'there' }},

Thank you for booking with **{{ config('app.name') }}**. We have received your reservation request.

**Reservation number:** {{ $reservation->reservation_number }}

**Vehicle:** {{ $vehicle?->name ?? '—' }}

**Pickup:** {{ $reservation->pickupLocation?->name ?? '—' }} — {{ $reservation->start_datetime?->format('d/m/Y H:i') }}

**Drop-off:** {{ $reservation->dropoffLocation?->name ?? '—' }} — {{ $reservation->end_datetime?->format('d/m/Y H:i') }}

**Duration:** {{ $reservation->total_days }} day(s)

**Estimated total:** {{ number_format((float) $reservation->total_price, 2, '.', ' ') }} MAD

Our team will contact you soon by phone to confirm your booking. Please keep this reservation number.

If you have any questions, reply to this email or call us.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
