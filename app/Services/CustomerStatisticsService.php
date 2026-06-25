<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\Reservation;
use App\Models\ReservationStatus;
use App\Models\Vehicle;
use Illuminate\Support\Collection;

class CustomerStatisticsService
{
    /**
     * @return array<string, mixed>
     */
    public function forCustomer(Customer $customer): array
    {
        $cancelledStatusIds = ReservationStatus::query()
            ->whereIn('slug', ['cancelled', 'rejected'])
            ->pluck('id');

        $completedStatusId = ReservationStatus::query()
            ->where('slug', 'completed')
            ->value('id');

        $activeStatusIds = ReservationStatus::query()
            ->whereIn('slug', ['pending', 'confirmed', 'in_progress'])
            ->pluck('id');

        $paidStatusId = PaymentStatus::query()
            ->where('slug', 'paid')
            ->value('id');

        $baseQuery = Reservation::query()->where('customer_id', $customer->id);

        $reservationsCount = (clone $baseQuery)->count();
        $completedCount = $completedStatusId
            ? (clone $baseQuery)->where('status_id', $completedStatusId)->count()
            : 0;
        $cancelledCount = $cancelledStatusIds->isNotEmpty()
            ? (clone $baseQuery)->whereIn('status_id', $cancelledStatusIds)->count()
            : 0;
        $activeCount = $activeStatusIds->isNotEmpty()
            ? (clone $baseQuery)->whereIn('status_id', $activeStatusIds)->count()
            : 0;

        $bookedQuery = (clone $baseQuery);
        if ($cancelledStatusIds->isNotEmpty()) {
            $bookedQuery->whereNotIn('status_id', $cancelledStatusIds);
        }

        $totalBooked = (float) (clone $bookedQuery)->sum('total_price');
        $totalDays = (int) (clone $bookedQuery)->sum('total_days');

        $reservationIds = (clone $baseQuery)->pluck('id');

        $totalPaid = $paidStatusId && $reservationIds->isNotEmpty()
            ? (float) Payment::query()
                ->whereIn('reservation_id', $reservationIds)
                ->where('payment_status_id', $paidStatusId)
                ->sum('amount')
            : 0.0;

        $bookedCount = (clone $bookedQuery)->count();
        $averageBookingValue = $bookedCount > 0 ? round($totalBooked / $bookedCount, 2) : 0.0;

        $firstReservationAt = (clone $baseQuery)->min('created_at');
        $lastReservationAt = (clone $baseQuery)->max('created_at');

        $favoriteVehicleRow = (clone $baseQuery)
            ->selectRaw('vehicle_id, COUNT(*) as rentals_count')
            ->groupBy('vehicle_id')
            ->orderByDesc('rentals_count')
            ->first();

        $favoriteVehicle = null;
        if ($favoriteVehicleRow) {
            $vehicle = Vehicle::query()->find($favoriteVehicleRow->vehicle_id);
            if ($vehicle) {
                $favoriteVehicle = [
                    'id' => $vehicle->id,
                    'name' => $vehicle->name,
                    'rentals_count' => (int) $favoriteVehicleRow->rentals_count,
                ];
            }
        }

        return [
            'reservations_count' => $reservationsCount,
            'completed_count' => $completedCount,
            'cancelled_count' => $cancelledCount,
            'active_count' => $activeCount,
            'total_days' => $totalDays,
            'total_booked' => $this->money($totalBooked),
            'total_paid' => $this->money($totalPaid),
            'total_outstanding' => $this->money(max(0, $totalBooked - $totalPaid)),
            'average_booking_value' => $this->money($averageBookingValue),
            'first_reservation_at' => $firstReservationAt,
            'last_reservation_at' => $lastReservationAt,
            'favorite_vehicle' => $favoriteVehicle,
        ];
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function recentReservations(Customer $customer, int $limit = 10): Collection
    {
        return $customer->reservations()
            ->with(['status', 'paymentStatus', 'vehicle'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    private function money(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }
}
