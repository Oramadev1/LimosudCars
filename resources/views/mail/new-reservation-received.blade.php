@php
    $customer = $reservation->customer;
    $vehicle = $reservation->vehicle;
@endphp

<x-mail::message>
# New reservation request

**Reservation:** {{ $reservation->reservation_number }}

**Customer:** {{ $customer?->full_name ?? '—' }}

**Phone:** {{ $customer?->phone ?? '—' }}

@if ($customer?->email)
**Email:** {{ $customer->email }}
@endif

**Vehicle:** {{ $vehicle?->name ?? '—' }}

**Pickup:** {{ $reservation->pickupLocation?->name ?? '—' }} — {{ $reservation->start_datetime?->format('d/m/Y H:i') }}

**Drop-off:** {{ $reservation->dropoffLocation?->name ?? '—' }} — {{ $reservation->end_datetime?->format('d/m/Y H:i') }}

**Duration:** {{ $reservation->total_days }} day(s)

**Total:** {{ number_format((float) $reservation->total_price, 2, '.', ' ') }} MAD

@if ($reservation->customer_notes)
**Customer notes:**

{{ $reservation->customer_notes }}
@endif

Review this request in the admin dashboard and confirm or reject it.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
