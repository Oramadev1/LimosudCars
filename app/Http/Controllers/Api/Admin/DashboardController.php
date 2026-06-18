<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardStatisticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @group Dashboard
 *
 * Admin dashboard statistics and reporting endpoints. Requires `dashboard.view`.
 */
class DashboardController extends Controller
{
    /**
     * Get global dashboard KPIs.
     *
     * Requires permission: `dashboard.view`.
     *
     * @queryParam year integer optional KPI year. Example: 2026
     * @queryParam month integer optional KPI month. Example: 6
     */
    public function statistics(Request $request, DashboardStatisticsService $statisticsService): JsonResponse
    {
        $data = $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        $month = isset($data['year'], $data['month'])
            ? Carbon::create((int) $data['year'], (int) $data['month'], 1)
            : now();

        return response()->json($statisticsService->overview($month));
    }

    /**
     * Get revenue reporting totals.
     *
     * Requires permission: `dashboard.view`.
     *
     * @queryParam start_date date optional Start date. Example: 2026-06-01
     * @queryParam end_date date optional End date. Example: 2026-06-30
     * @queryParam group_by string optional Group revenue by day or month. Example: day
     */
    public function revenue(Request $request, DashboardStatisticsService $statisticsService): JsonResponse
    {
        [$startDate, $endDate, $groupBy] = $this->reportParameters($request);

        return response()->json($statisticsService->revenueReport($startDate, $endDate, $groupBy));
    }

    /**
     * Get expense reporting totals.
     *
     * Requires permission: `dashboard.view`.
     *
     * @queryParam start_date date optional Start date. Example: 2026-06-01
     * @queryParam end_date date optional End date. Example: 2026-06-30
     * @queryParam group_by string optional Group expenses by day or month. Example: month
     */
    public function expenses(Request $request, DashboardStatisticsService $statisticsService): JsonResponse
    {
        [$startDate, $endDate, $groupBy] = $this->reportParameters($request);

        return response()->json($statisticsService->expenseReport($startDate, $endDate, $groupBy));
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: string}
     */
    private function reportParameters(Request $request): array
    {
        $data = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'group_by' => ['nullable', 'string', 'in:day,month'],
        ]);

        return [
            isset($data['start_date']) ? Carbon::parse($data['start_date'])->startOfDay() : now()->startOfMonth(),
            isset($data['end_date']) ? Carbon::parse($data['end_date'])->endOfDay() : now()->endOfMonth(),
            $data['group_by'] ?? 'day',
        ];
    }
}
