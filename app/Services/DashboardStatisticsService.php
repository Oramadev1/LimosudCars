<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\Reservation;
use App\Models\ReservationStatus;
use App\Models\Vehicle;
use App\Models\VehicleMaintenance;
use App\Models\VehicleStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardStatisticsService
{
    /**
     * @return array<string, mixed>
     */
    public function overview(?Carbon $month = null): array
    {
        $month ??= now();
        $monthlyRevenue = $this->paidPaymentsBetween($month->copy()->startOfMonth(), $month->copy()->endOfMonth())->sum('amount');
        $monthlyExpenses = $this->monthlyExpensesForPeriod($month->copy()->startOfMonth(), $month->copy()->endOfMonth());

        return [
            'global_kpis' => [
                'total_vehicles' => Vehicle::count(),
                'available_vehicles' => $this->vehicleCountByStatus('available'),
                'reserved_vehicles' => $this->vehicleCountByStatus('reserved'),
                'rented_vehicles' => $this->vehicleCountByStatus('rented'),
                'vehicles_in_maintenance' => $this->vehicleCountByStatus('maintenance'),
                'vehicles_in_repair' => $this->vehicleCountByStatus('repair'),
                'out_of_service_vehicles' => $this->vehicleCountByStatus('out_of_service'),
                'total_customers' => Customer::count(),
                'total_reservations' => Reservation::count(),
                'reservations_today' => $this->reservationsTodayCount(),
                'reservations_this_month' => $this->reservationsThisMonthCount($month),
                'confirmed_reservations' => $this->reservationCountByStatus('confirmed'),
                'in_progress_reservations' => $this->reservationCountByStatus('in_progress'),
                'completed_reservations' => $this->reservationCountByStatus('completed'),
                'cancelled_reservations' => $this->reservationCountByStatus('cancelled'),
                'unpaid_reservations' => $this->reservationCountByPaymentStatus('unpaid'),
                'partial_paid_reservations' => $this->reservationCountByPaymentStatus('partial_paid'),
                'paid_reservations' => $this->reservationCountByPaymentStatus('paid'),
                'monthly_revenue' => $this->money($monthlyRevenue),
                'monthly_expenses' => $this->money($monthlyExpenses),
                'monthly_net_profit' => $this->money($monthlyRevenue - $monthlyExpenses),
            ],
            'month' => [
                'year' => $month->year,
                'month' => $month->month,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function revenueReport(Carbon $startDate, Carbon $endDate, string $groupBy = 'day'): array
    {
        return [
            'daily_revenue' => $this->money($this->paidPaymentsBetween(now()->startOfDay(), now()->endOfDay())->sum('amount')),
            'monthly_revenue' => $this->money($this->paidPaymentsBetween(now()->startOfMonth(), now()->endOfMonth())->sum('amount')),
            'yearly_revenue' => $this->money($this->paidPaymentsBetween(now()->startOfYear(), now()->endOfYear())->sum('amount')),
            'date_range_revenue' => $this->money($this->paidPaymentsBetween($startDate, $endDate)->sum('amount')),
            'date_range' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'group_by' => $groupBy,
            'grouped_revenue' => $this->groupedPaidPayments($startDate, $endDate, $groupBy),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function expenseReport(Carbon $startDate, Carbon $endDate, string $groupBy = 'day'): array
    {
        $rangeQuery = Expense::query()
            ->whereDate('expense_date', '>=', $startDate->toDateString())
            ->whereDate('expense_date', '<=', $endDate->toDateString());

        return [
            'monthly_expenses' => $this->money($this->monthlyExpensesForPeriod(now()->startOfMonth(), now()->endOfMonth())),
            'date_range_expenses' => $this->money((clone $rangeQuery)->sum('amount')),
            'date_range' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'group_by' => $groupBy,
            'grouped_expenses' => $this->groupedExpenses($startDate, $endDate, $groupBy),
            'expenses_by_category' => $this->expensesByCategory($startDate, $endDate),
            'expenses_by_vehicle' => $this->expensesByVehicle($startDate, $endDate),
        ];
    }

    /**
     * @return Builder<Payment>
     */
    private function paidPaymentsBetween(Carbon $startDate, Carbon $endDate): Builder
    {
        $paidStatusId = PaymentStatus::where('slug', 'paid')->value('id');

        if ($paidStatusId === null) {
            return Payment::query()->whereRaw('1 = 0');
        }

        return Payment::query()
            ->where('payment_status_id', $paidStatusId)
            ->whereDate('payment_date', '>=', $startDate->toDateString())
            ->whereDate('payment_date', '<=', $endDate->toDateString());
    }

    private function vehicleCountByStatus(string $slug): int
    {
        $statusId = VehicleStatus::where('slug', $slug)->value('id');

        if ($statusId === null) {
            return 0;
        }

        return Vehicle::query()
            ->where('status_id', $statusId)
            ->count();
    }

    private function reservationCountByStatus(string $slug): int
    {
        $statusId = ReservationStatus::where('slug', $slug)->value('id');

        if ($statusId === null) {
            return 0;
        }

        return Reservation::query()
            ->where('status_id', $statusId)
            ->count();
    }

    private function reservationsTodayCount(): int
    {
        return Reservation::query()
            ->whereDate('created_at', now()->toDateString())
            ->count();
    }

    private function reservationsThisMonthCount(Carbon $month): int
    {
        return Reservation::query()
            ->whereDate('created_at', '>=', $month->copy()->startOfMonth()->toDateString())
            ->whereDate('created_at', '<=', $month->copy()->endOfMonth()->toDateString())
            ->count();
    }

    private function monthlyExpensesForPeriod(Carbon $startDate, Carbon $endDate): float
    {
        return (float) Expense::query()
            ->whereDate('expense_date', '>=', $startDate->toDateString())
            ->whereDate('expense_date', '<=', $endDate->toDateString())
            ->sum('amount')
            + $this->maintenanceCostsWithoutExpenseBetween($startDate, $endDate);
    }

    private function maintenanceCostsWithoutExpenseBetween(Carbon $startDate, Carbon $endDate): float
    {
        return (float) VehicleMaintenance::query()
            ->whereDate('maintenance_date', '>=', $startDate->toDateString())
            ->whereDate('maintenance_date', '<=', $endDate->toDateString())
            ->where('cost', '>', 0)
            ->whereNotExists(function ($query): void {
                $query->select(DB::raw(1))
                    ->from('expenses')
                    ->whereColumn('expenses.vehicle_id', 'vehicle_maintenances.vehicle_id')
                    ->whereColumn('expenses.amount', 'vehicle_maintenances.cost')
                    ->whereColumn('expenses.expense_date', 'vehicle_maintenances.maintenance_date')
                    ->whereNull('expenses.deleted_at');
            })
            ->sum('cost');
    }

    private function reservationCountByPaymentStatus(string $slug): int
    {
        $statusId = PaymentStatus::where('slug', $slug)->value('id');

        if ($statusId === null) {
            return 0;
        }

        return Reservation::query()
            ->where('payment_status_id', $statusId)
            ->count();
    }

    /**
     * @return array<int, array{period: string, total_amount: float, payment_count: int}>
     */
    private function groupedPaidPayments(Carbon $startDate, Carbon $endDate, string $groupBy): array
    {
        $periodExpression = $this->periodExpression('payment_date', $groupBy);

        return $this->paidPaymentsBetween($startDate, $endDate)
            ->selectRaw("{$periodExpression} as period")
            ->selectRaw('SUM(amount) as total_amount')
            ->selectRaw('COUNT(*) as payment_count')
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(fn (Payment $payment): array => [
                'period' => (string) $payment->period,
                'total_amount' => $this->money($payment->total_amount),
                'payment_count' => (int) $payment->payment_count,
            ])
            ->all();
    }

    /**
     * @return array<int, array{period: string, total_amount: float, expense_count: int}>
     */
    private function groupedExpenses(Carbon $startDate, Carbon $endDate, string $groupBy): array
    {
        $periodExpression = $this->periodExpression('expense_date', $groupBy);

        return Expense::query()
            ->whereDate('expense_date', '>=', $startDate->toDateString())
            ->whereDate('expense_date', '<=', $endDate->toDateString())
            ->selectRaw("{$periodExpression} as period")
            ->selectRaw('SUM(amount) as total_amount')
            ->selectRaw('COUNT(*) as expense_count')
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->map(fn (Expense $expense): array => [
                'period' => (string) $expense->period,
                'total_amount' => $this->money($expense->total_amount),
                'expense_count' => (int) $expense->expense_count,
            ])
            ->all();
    }

    /**
     * @return array<int, array{slug: string, name: string, total_amount: float, expense_count: int}>
     */
    private function expensesByCategory(Carbon $startDate, Carbon $endDate): array
    {
        $categories = Expense::query()
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->whereDate('expense_date', '>=', $startDate->toDateString())
            ->whereDate('expense_date', '<=', $endDate->toDateString())
            ->selectRaw('expense_categories.slug as slug, expense_categories.name as name')
            ->selectRaw('SUM(expenses.amount) as total_amount')
            ->selectRaw('COUNT(*) as expense_count')
            ->groupBy('expense_categories.slug', 'expense_categories.name')
            ->get()
            ->keyBy('slug');

        $maintenanceExtra = $this->maintenanceCostsWithoutExpenseBetween($startDate, $endDate);

        if ($maintenanceExtra > 0) {
            if ($categories->has('maintenance')) {
                $row = $categories->get('maintenance');
                $row->total_amount = (float) $row->total_amount + $maintenanceExtra;
            } else {
                $categories->put('maintenance', (object) [
                    'slug' => 'maintenance',
                    'name' => 'Maintenance',
                    'total_amount' => $maintenanceExtra,
                    'expense_count' => 0,
                ]);
            }
        }

        return $categories
            ->sortByDesc(fn ($row): float => (float) $row->total_amount)
            ->values()
            ->map(fn ($row): array => [
                'slug' => $row->slug,
                'name' => $row->name,
                'total_amount' => $this->money($row->total_amount),
                'expense_count' => (int) $row->expense_count,
            ])
            ->all();
    }

    /**
     * @return array<int, array{id: int|null, name: string, plate_number: string|null, total_amount: float, expense_count: int}>
     */
    private function expensesByVehicle(Carbon $startDate, Carbon $endDate): array
    {
        return Expense::query()
            ->leftJoin('vehicles', 'expenses.vehicle_id', '=', 'vehicles.id')
            ->whereDate('expense_date', '>=', $startDate->toDateString())
            ->whereDate('expense_date', '<=', $endDate->toDateString())
            ->selectRaw('vehicles.id as id, COALESCE(vehicles.name, ?) as name, vehicles.plate_number as plate_number', ['General'])
            ->selectRaw('SUM(expenses.amount) as total_amount')
            ->selectRaw('COUNT(*) as expense_count')
            ->groupBy('vehicles.id', 'vehicles.name', 'vehicles.plate_number')
            ->orderByDesc('total_amount')
            ->get()
            ->map(fn ($row): array => [
                'id' => $row->id === null ? null : (int) $row->id,
                'name' => $row->name,
                'plate_number' => $row->plate_number,
                'total_amount' => $this->money($row->total_amount),
                'expense_count' => (int) $row->expense_count,
            ])
            ->all();
    }

    private function periodExpression(string $column, string $groupBy): string
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            return $groupBy === 'month'
                ? "strftime('%Y-%m', {$column})"
                : "strftime('%Y-%m-%d', {$column})";
        }

        return $groupBy === 'month'
            ? "DATE_FORMAT({$column}, '%Y-%m')"
            : "DATE_FORMAT({$column}, '%Y-%m-%d')";
    }

    private function money(mixed $amount): float
    {
        return round((float) $amount, 2);
    }
}
