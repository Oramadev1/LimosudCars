<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\AlertStatus;
use App\Models\AlertType;
use App\Models\VehicleDocument;
use App\Models\VehicleMaintenance;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AlertService
{
    public function createAlert(array $data): Alert
    {
        return DB::transaction(function () use ($data): Alert {
            $alertTypeId = $this->alertTypeId($data['alert_type_slug']);
            $statusId = $this->alertStatusId($data['alert_status_slug'] ?? 'pending');

            if (($data['alert_status_slug'] ?? 'pending') === 'pending') {
                $existing = $this->pendingDuplicate($data['vehicle_id'] ?? null, $alertTypeId, $data['due_date'] ?? null);

                if ($existing) {
                    return $existing;
                }
            }

            return Alert::create([
                'vehicle_id' => $data['vehicle_id'] ?? null,
                'alert_type_id' => $alertTypeId,
                'alert_status_id' => $statusId,
                'title' => $data['title'],
                'message' => $data['message'] ?? null,
                'due_date' => $data['due_date'] ?? null,
            ]);
        });
    }

    public function markSeen(Alert $alert): Alert
    {
        return $this->transition($alert, 'seen', ['pending']);
    }

    public function markDone(Alert $alert): Alert
    {
        return $this->transition($alert, 'done', ['pending', 'seen']);
    }

    public function ignore(Alert $alert): Alert
    {
        return $this->transition($alert, 'ignored', ['pending', 'seen']);
    }

    public function generateMaintenanceAlerts(): int
    {
        $count = 0;

        VehicleMaintenance::query()
            ->whereNotNull('next_maintenance_date')
            ->whereDate('next_maintenance_date', '<=', now()->addDays(30)->toDateString())
            ->with('vehicle')
            ->each(function (VehicleMaintenance $maintenance) use (&$count): void {
                $before = Alert::count();

                $this->createAlert([
                    'vehicle_id' => $maintenance->vehicle_id,
                    'alert_type_slug' => 'maintenance_due',
                    'title' => 'Maintenance due for '.$maintenance->vehicle->name,
                    'message' => 'Next maintenance is due on '.$maintenance->next_maintenance_date?->toDateString().'.',
                    'due_date' => $maintenance->next_maintenance_date?->toDateString(),
                ]);

                $count += Alert::count() > $before ? 1 : 0;
            });

        return $count;
    }

    public function generateDocumentExpiryAlerts(): int
    {
        $count = 0;

        VehicleDocument::query()
            ->whereNotNull('expires_at')
            ->whereDate('expires_at', '<=', now()->addDays(30)->toDateString())
            ->with(['vehicle', 'documentType'])
            ->each(function (VehicleDocument $document) use (&$count): void {
                $before = Alert::count();

                $this->createAlert([
                    'vehicle_id' => $document->vehicle_id,
                    'alert_type_slug' => 'document_expiry',
                    'title' => 'Document expiring for '.$document->vehicle->name,
                    'message' => ($document->documentType?->name ?? 'Document').' expires on '.$document->expires_at?->toDateString().'.',
                    'due_date' => $document->expires_at?->toDateString(),
                ]);

                $count += Alert::count() > $before ? 1 : 0;
            });

        return $count;
    }

    private function transition(Alert $alert, string $targetStatusSlug, array $allowedCurrentSlugs): Alert
    {
        return DB::transaction(function () use ($alert, $targetStatusSlug, $allowedCurrentSlugs): Alert {
            $alert = Alert::whereKey($alert->id)->lockForUpdate()->firstOrFail();
            $alert->load('alertStatus');

            if (! in_array($alert->alertStatus->slug, $allowedCurrentSlugs, true)) {
                throw ValidationException::withMessages([
                    'status' => 'This alert status transition is not allowed.',
                ]);
            }

            $alert->update([
                'alert_status_id' => $this->alertStatusId($targetStatusSlug),
            ]);

            return $alert;
        });
    }

    private function pendingDuplicate(?int $vehicleId, int $alertTypeId, ?string $dueDate): ?Alert
    {
        $query = Alert::query()
            ->where('alert_status_id', $this->alertStatusId('pending'))
            ->where('alert_type_id', $alertTypeId)
            ->where('vehicle_id', $vehicleId);

        $dueDate === null
            ? $query->whereNull('due_date')
            : $query->whereDate('due_date', $dueDate);

        return $query->first();
    }

    private function alertTypeId(string $slug): int
    {
        return AlertType::where('slug', $slug)->firstOrFail()->id;
    }

    private function alertStatusId(string $slug): int
    {
        return AlertStatus::where('slug', $slug)->firstOrFail()->id;
    }
}
