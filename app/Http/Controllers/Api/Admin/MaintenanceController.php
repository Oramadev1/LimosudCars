<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMaintenanceRequest;
use App\Http\Requests\Admin\UpdateMaintenanceRequest;
use App\Http\Resources\VehicleMaintenanceResource;
use App\Models\ExpenseCategory;
use App\Models\MaintenanceType;
use App\Models\Vehicle;
use App\Models\VehicleMaintenance;
use App\Models\VehicleStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * @group Maintenance
 *
 * Admin vehicle maintenance endpoints. Requires the matching `maintenance.*` permission listed on each endpoint.
 */
class MaintenanceController extends Controller
{
    /**
     * List maintenance records.
     *
     * Requires permission: `maintenance.view`.
     */
    public function index(): AnonymousResourceCollection
    {
        $maintenances = VehicleMaintenance::query()
            ->with($this->relationships())
            ->latest()
            ->paginate(15);

        return VehicleMaintenanceResource::collection($maintenances);
    }

    /**
     * Create a maintenance record.
     *
     * Requires permission: `maintenance.create`.
     *
     * @bodyParam vehicle_id integer required Vehicle ID. Example: 1
     * @bodyParam maintenance_type_slug string required Maintenance type slug. Example: oil_change
     * @bodyParam maintenance_date date required Maintenance date. Example: 2026-06-10
     * @bodyParam next_maintenance_date date optional Next scheduled maintenance date. Example: 2026-07-10
     * @bodyParam mileage integer optional Vehicle mileage. Example: 21000
     * @bodyParam cost number optional Maintenance cost. Example: 450
     * @bodyParam garage_name string optional Garage name. Example: Dakhla Garage
     * @bodyParam notes string optional Maintenance notes. Example: Routine service.
     * @bodyParam vehicle_status_slug string optional Set vehicle status to maintenance or repair. Example: maintenance
     * @bodyParam create_expense boolean optional Create an expense from the maintenance cost. Example: true
     * @bodyParam expense_category_slug string optional Expense category slug when create_expense is true. Example: maintenance
     */
    public function store(StoreMaintenanceRequest $request): JsonResponse
    {
        $maintenance = DB::transaction(function () use ($request): VehicleMaintenance {
            $data = $request->validated();
            $data = $this->applyDefaultExpenseSync($data);
            $maintenance = VehicleMaintenance::create($this->maintenanceData($data));

            $this->updateVehicleStatusIfRequested($maintenance->vehicle, $data['vehicle_status_slug'] ?? null);
            $this->createExpenseIfRequested($maintenance, $data, $request->user()?->id);

            return $maintenance;
        });

        return (new VehicleMaintenanceResource($maintenance->load($this->relationships())))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display a maintenance record.
     *
     * Requires permission: `maintenance.view`.
     */
    public function show(VehicleMaintenance $maintenance): VehicleMaintenanceResource
    {
        return new VehicleMaintenanceResource($maintenance->load($this->relationships()));
    }

    /**
     * Update a maintenance record.
     *
     * Requires permission: `maintenance.update`.
     *
     * @bodyParam next_maintenance_date date optional Next scheduled maintenance date. Example: 2026-08-10
     * @bodyParam cost number optional Maintenance cost. Example: 500
     * @bodyParam vehicle_status_slug string optional Set vehicle status to maintenance or repair. Example: repair
     */
    public function update(UpdateMaintenanceRequest $request, VehicleMaintenance $maintenance): VehicleMaintenanceResource
    {
        $maintenance = DB::transaction(function () use ($request, $maintenance): VehicleMaintenance {
            $data = $request->validated();
            $maintenance->update($this->maintenanceData($data, partial: true));
            $this->updateVehicleStatusIfRequested($maintenance->vehicle, $data['vehicle_status_slug'] ?? null);

            return $maintenance;
        });

        return new VehicleMaintenanceResource($maintenance->load($this->relationships()));
    }

    /**
     * Soft delete a maintenance record.
     *
     * Requires permission: `maintenance.delete`.
     */
    public function destroy(VehicleMaintenance $maintenance): Response
    {
        $maintenance->delete();

        return response()->noContent();
    }

    /**
     * List maintenance records for one vehicle.
     *
     * Requires permission: `maintenance.view`.
     */
    public function forVehicle(Vehicle $vehicle): AnonymousResourceCollection
    {
        $maintenances = $vehicle->maintenances()
            ->with($this->relationships())
            ->latest()
            ->paginate(15);

        return VehicleMaintenanceResource::collection($maintenances);
    }

    /**
     * List upcoming maintenance records.
     *
     * Requires permission: `maintenance.view`.
     */
    public function upcoming(): AnonymousResourceCollection
    {
        $maintenances = VehicleMaintenance::query()
            ->with($this->relationships())
            ->whereNotNull('next_maintenance_date')
            ->whereDate('next_maintenance_date', '>=', now()->toDateString())
            ->orderBy('next_maintenance_date')
            ->paginate(15);

        return VehicleMaintenanceResource::collection($maintenances);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function applyDefaultExpenseSync(array $data): array
    {
        if (($data['cost'] ?? 0) > 0 && ! array_key_exists('create_expense', $data)) {
            $data['create_expense'] = true;
            $data['expense_category_slug'] ??= 'maintenance';
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function maintenanceData(array $data, bool $partial = false): array
    {
        $prepared = [];

        foreach (['vehicle_id', 'maintenance_date', 'next_maintenance_date', 'mileage', 'cost', 'garage_name', 'notes'] as $field) {
            if (array_key_exists($field, $data)) {
                $prepared[$field] = $data[$field];
            }
        }

        if (array_key_exists('cost', $prepared) && $prepared['cost'] === null) {
            $prepared['cost'] = 0;
        }

        if (array_key_exists('maintenance_type_slug', $data)) {
            $typeId = MaintenanceType::where('slug', $data['maintenance_type_slug'])->value('id');

            if ($typeId === null) {
                throw ValidationException::withMessages([
                    'maintenance_type_slug' => 'The selected maintenance type is invalid.',
                ]);
            }

            $prepared['maintenance_type_id'] = $typeId;
        }

        return $partial ? $prepared : array_merge(['cost' => 0], $prepared);
    }

    private function updateVehicleStatusIfRequested(Vehicle $vehicle, ?string $statusSlug): void
    {
        if ($statusSlug === null) {
            return;
        }

        if (! in_array($statusSlug, ['maintenance', 'repair'], true)) {
            throw ValidationException::withMessages([
                'vehicle_status_slug' => 'Maintenance can only update the vehicle status to maintenance or repair.',
            ]);
        }

        $vehicle->update([
            'status_id' => VehicleStatus::where('slug', $statusSlug)->firstOrFail()->id,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function createExpenseIfRequested(VehicleMaintenance $maintenance, array $data, ?int $userId): void
    {
        if (! ($data['create_expense'] ?? false)) {
            return;
        }

        $categoryId = ExpenseCategory::where('slug', $data['expense_category_slug'] ?? '')->value('id');

        if ($categoryId === null) {
            throw ValidationException::withMessages([
                'expense_category_slug' => 'The selected expense category is invalid.',
            ]);
        }

        $maintenance->vehicle->expenses()->create([
            'expense_category_id' => $categoryId,
            'amount' => $data['cost'] ?? 0,
            'expense_date' => $data['maintenance_date'],
            'description' => 'Maintenance cost: '.($data['garage_name'] ?? 'Vehicle maintenance'),
            'created_by' => $userId,
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function relationships(): array
    {
        return ['vehicle', 'maintenanceType'];
    }
}
