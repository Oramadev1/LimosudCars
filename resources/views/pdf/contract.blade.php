<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contract {{ $contractNumber }}</title>
    <style>
        body {
            color: #111827;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        h1, h2 {
            margin-bottom: 8px;
        }

        table {
            border-collapse: collapse;
            margin-bottom: 18px;
            width: 100%;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f3f4f6;
            width: 35%;
        }

        .brand {
            margin-bottom: 20px;
            text-align: center;
        }

        .brand img {
            height: 90px;
            width: auto;
        }

        .signature {
            margin-top: 48px;
        }
    </style>
</head>
<body>
    @if (!empty($logoData))
        <div class="brand">
            <img src="data:image/jpeg;base64,{{ $logoData }}" alt="Limosud Cars">
        </div>
    @endif

    <h1>Rental Contract</h1>
    <p><strong>Contract Number:</strong> {{ $contractNumber }}</p>
    <p><strong>Reservation Number:</strong> {{ $reservation->reservation_number }}</p>

    <h2>Customer</h2>
    <table>
        <tr><th>Name</th><td>{{ $reservation->customer->full_name }}</td></tr>
        <tr><th>Nationality</th><td>{{ $reservation->customer->nationality }}</td></tr>
        <tr><th>Phone</th><td>{{ $reservation->customer->phone }}</td></tr>
        <tr><th>Email</th><td>{{ $reservation->customer->email }}</td></tr>
        <tr><th>Passport / CIN</th><td>{{ $reservation->customer->passport_or_cin }}</td></tr>
        <tr><th>Driving License</th><td>{{ $reservation->customer->driving_license_number }}</td></tr>
    </table>

    <h2>Vehicle</h2>
    <table>
        <tr><th>Vehicle</th><td>{{ $reservation->vehicle->name }}</td></tr>
        <tr><th>Brand</th><td>{{ $reservation->vehicle->brand->name }}</td></tr>
        <tr><th>Model</th><td>{{ $reservation->vehicle->model }} {{ $reservation->vehicle->year }}</td></tr>
        <tr><th>Plate Number</th><td>{{ $reservation->vehicle->plate_number }}</td></tr>
    </table>

    <h2>Rental</h2>
    <table>
        <tr><th>Pickup</th><td>{{ $reservation->pickupLocation->name }} - {{ $reservation->start_datetime }}</td></tr>
        <tr><th>Dropoff</th><td>{{ $reservation->dropoffLocation->name }} - {{ $reservation->end_datetime }}</td></tr>
        <tr><th>Total Days</th><td>{{ $reservation->total_days }}</td></tr>
        <tr><th>Price Per Day</th><td>{{ number_format((float) $reservation->price_per_day, 2) }}</td></tr>
        <tr><th>Delivery Fee</th><td>{{ number_format((float) $reservation->delivery_fee, 2) }}</td></tr>
        <tr><th>Deposit</th><td>{{ number_format((float) $reservation->deposit_amount, 2) }}</td></tr>
        <tr><th>Total Price</th><td>{{ number_format((float) $reservation->total_price, 2) }}</td></tr>
    </table>

    <h2>Payment Summary</h2>
    <table>
        <tr><th>Paid Amount</th><td>{{ number_format($paidAmount, 2) }}</td></tr>
        <tr><th>Remaining Amount</th><td>{{ number_format($remainingAmount, 2) }}</td></tr>
        <tr><th>Payment Status</th><td>{{ $reservation->paymentStatus->name }}</td></tr>
    </table>

    <div class="signature">
        <p>Customer Signature: ______________________________</p>
        <p>Agency Representative: ______________________</p>
    </div>
</body>
</html>
