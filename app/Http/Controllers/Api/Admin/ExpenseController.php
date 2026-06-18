<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\StoreExpenseRequest;
use App\Http\Requests\Admin\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @group Expenses
 *
 * Admin expense endpoints. Requires the matching `expenses.*` permission listed on each endpoint.
 */
class ExpenseController extends Controller
{
    /**
     * List expenses.
     *
     * Requires permission: `expenses.view`.
     */
    public function index(): AnonymousResourceCollection
    {
        $expenses = Expense::query()
            ->with($this->relationships())
            ->latest()
            ->paginate(15);

        return ExpenseResource::collection($expenses);
    }

    /**
     * Create an expense.
     *
     * Requires permission: `expenses.create`.
     *
     * @bodyParam vehicle_id integer optional Vehicle ID. Example: 1
     * @bodyParam expense_category_slug string required Expense category slug. Example: fuel
     * @bodyParam amount number required Expense amount. Example: 200
     * @bodyParam expense_date date required Expense date. Example: 2026-06-10
     * @bodyParam description string optional Expense description. Example: Diesel refill.
     * @bodyParam invoice file optional PDF or image invoice file.
     */
    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $expense = DB::transaction(function () use ($request): Expense {
            $data = $this->expenseData($request->validated());

            if ($request->hasFile('invoice')) {
                $data['invoice_path'] = $request->file('invoice')->store('expenses/invoices', 'local');
            }

            $data['created_by'] = $request->user()?->id;

            return Expense::create($data);
        });

        return (new ExpenseResource($expense->load($this->relationships())))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display an expense.
     *
     * Requires permission: `expenses.view`.
     */
    public function show(Expense $expense): ExpenseResource
    {
        return new ExpenseResource($expense->load($this->relationships()));
    }

    /**
     * Update an expense.
     *
     * Requires permission: `expenses.update`.
     *
     * @bodyParam expense_category_slug string optional Expense category slug. Example: maintenance
     * @bodyParam amount number optional Expense amount. Example: 250
     * @bodyParam description string optional Expense description. Example: Updated invoice details.
     * @bodyParam invoice file optional Replacement invoice file.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense): ExpenseResource
    {
        $expense = DB::transaction(function () use ($request, $expense): Expense {
            $data = $this->expenseData($request->validated());

            if ($request->hasFile('invoice')) {
                if ($expense->invoice_path !== null) {
                    Storage::disk('local')->delete($expense->invoice_path);
                }

                $data['invoice_path'] = $request->file('invoice')->store('expenses/invoices', 'local');
            }

            $expense->update($data);

            return $expense;
        });

        return new ExpenseResource($expense->load($this->relationships()));
    }

    /**
     * Soft delete an expense.
     *
     * Requires permission: `expenses.delete`.
     */
    public function destroy(Expense $expense): Response
    {
        $expense->delete();

        return response()->noContent();
    }

    /**
     * List expenses for one vehicle.
     *
     * Requires permission: `expenses.view`.
     */
    public function forVehicle(Vehicle $vehicle): AnonymousResourceCollection
    {
        $expenses = $vehicle->expenses()
            ->with($this->relationships())
            ->latest()
            ->paginate(15);

        return ExpenseResource::collection($expenses);
    }

    /**
     * Get monthly expense totals by category.
     *
     * Requires permission: `expenses.view`.
     *
     * @queryParam year integer optional Year to summarize. Example: 2026
     * @queryParam month integer optional Month to summarize. Example: 6
     */
    public function monthlySummary(Request $request): JsonResponse
    {
        $data = $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        $year = $data['year'] ?? now()->year;
        $month = $data['month'] ?? now()->month;

        $expenses = Expense::query()
            ->with('expenseCategory')
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->get();

        return response()->json([
            'year' => $year,
            'month' => $month,
            'total_amount' => round((float) $expenses->sum('amount'), 2),
            'expense_count' => $expenses->count(),
            'by_category' => $expenses
                ->groupBy(fn (Expense $expense): string => $expense->expenseCategory->slug)
                ->map(fn ($items, string $slug): array => [
                    'slug' => $slug,
                    'name' => $items->first()->expenseCategory->name,
                    'total_amount' => round((float) $items->sum('amount'), 2),
                    'expense_count' => $items->count(),
                ])
                ->values(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function expenseData(array $data): array
    {
        $prepared = [];

        foreach (['vehicle_id', 'amount', 'expense_date', 'description'] as $field) {
            if (array_key_exists($field, $data)) {
                $prepared[$field] = $data[$field];
            }
        }

        if (array_key_exists('expense_category_slug', $data)) {
            $prepared['expense_category_id'] = ExpenseCategory::where('slug', $data['expense_category_slug'])->firstOrFail()->id;
        }

        return $prepared;
    }

    /**
     * @return array<int, string>
     */
    private function relationships(): array
    {
        return ['vehicle', 'expenseCategory', 'createdBy'];
    }
}
