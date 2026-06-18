<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlertStatus;
use App\Models\AlertType;
use App\Models\ContractStatus;
use App\Models\DocumentType;
use App\Models\ExpenseCategory;
use App\Models\FuelType;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\MaintenanceType;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\ReservationSource;
use App\Models\ReservationStatus;
use App\Models\TransmissionType;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use App\Models\VehicleStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

/**
 * @group Public
 *
 * Lookup data for frontend forms.
 */
class LookupController extends Controller
{
    /**
     * Public safe lookup data.
     *
     * @unauthenticated
     */
    public function publicIndex(): JsonResponse
    {
        return response()->json([
            'vehicle_brands' => $this->brandLookups(publicOnly: true),
            'vehicle_categories' => $this->categoryLookups(publicOnly: true),
            'transmission_types' => $this->lookup(TransmissionType::class),
            'fuel_types' => $this->lookup(FuelType::class),
            'locations' => $this->locations(publicOnly: true),
        ]);
    }

    /**
     * Admin lookup data.
     *
     * Requires an authenticated admin token.
     */
    public function adminIndex(): JsonResponse
    {
        return response()->json([
            'vehicle_statuses' => $this->lookup(VehicleStatus::class),
            'transmission_types' => $this->lookup(TransmissionType::class),
            'fuel_types' => $this->lookup(FuelType::class),
            'reservation_statuses' => $this->lookup(ReservationStatus::class),
            'payment_statuses' => $this->lookup(PaymentStatus::class),
            'payment_methods' => $this->lookup(PaymentMethod::class),
            'payment_types' => $this->lookup(PaymentType::class),
            'reservation_sources' => $this->lookup(ReservationSource::class),
            'location_types' => $this->lookup(LocationType::class),
            'maintenance_types' => $this->lookup(MaintenanceType::class),
            'expense_categories' => $this->lookup(ExpenseCategory::class),
            'alert_types' => $this->lookup(AlertType::class),
            'alert_statuses' => $this->lookup(AlertStatus::class),
            'document_types' => $this->lookup(DocumentType::class),
            'contract_statuses' => $this->lookup(ContractStatus::class),
            'vehicle_brands' => $this->brandLookups(publicOnly: false),
            'vehicle_categories' => $this->categoryLookups(publicOnly: false),
            'locations' => $this->locations(publicOnly: false),
        ]);
    }

    /**
     * @param  class-string<Model>  $model
     * @return array<int, array<string, mixed>>
     */
    private function lookup(string $model): array
    {
        /** @var Collection<int, Model> $items */
        $items = $model::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return $items
            ->map(fn (Model $item): array => [
                'id' => $item->getKey(),
                'name' => $item->getAttribute('name'),
                'slug' => $item->getAttribute('slug'),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function brandLookups(bool $publicOnly): array
    {
        return VehicleBrand::query()
            ->when($publicOnly, fn ($query) => $query->where('is_active', true))
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'image_path', 'is_active'])
            ->map(fn (VehicleBrand $brand): array => [
                'id' => $brand->id,
                'name' => $brand->name,
                'slug' => $brand->slug,
                'image_path' => $brand->image_path,
                'is_active' => $brand->is_active,
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function categoryLookups(bool $publicOnly): array
    {
        return VehicleCategory::query()
            ->when($publicOnly, fn ($query) => $query->where('is_active', true))
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description', 'is_active'])
            ->map(fn (VehicleCategory $category): array => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'is_active' => $category->is_active,
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function locations(bool $publicOnly): array
    {
        return Location::query()
            ->with('locationType')
            ->when($publicOnly, fn ($query) => $query->where('is_active', true))
            ->orderBy('name')
            ->get()
            ->map(fn (Location $location): array => [
                'id' => $location->id,
                'name' => $location->name,
                'slug' => $location->slug,
                'address' => $location->address,
                'delivery_fee' => $location->delivery_fee,
                'is_active' => $location->is_active,
                'location_type' => [
                    'id' => $location->locationType->id,
                    'name' => $location->locationType->name,
                    'slug' => $location->locationType->slug,
                ],
            ])
            ->values()
            ->all();
    }
}
