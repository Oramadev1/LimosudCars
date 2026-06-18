<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAlertRequest;
use App\Http\Resources\AlertResource;
use App\Models\Alert;
use App\Models\AlertStatus;
use App\Services\AlertService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Alerts
 *
 * Admin alert endpoints. Requires the matching `alerts.*` permission listed on each endpoint.
 */
class AlertController extends Controller
{
    /**
     * List alerts.
     *
     * Requires permission: `alerts.view`.
     */
    public function index(): AnonymousResourceCollection
    {
        $alerts = Alert::query()
            ->with($this->relationships())
            ->latest()
            ->paginate(15);

        return AlertResource::collection($alerts);
    }

    /**
     * List pending alerts.
     *
     * Requires permission: `alerts.view`.
     */
    public function pending(): AnonymousResourceCollection
    {
        $alerts = Alert::query()
            ->with($this->relationships())
            ->where('alert_status_id', AlertStatus::where('slug', 'pending')->firstOrFail()->id)
            ->orderBy('due_date')
            ->paginate(15);

        return AlertResource::collection($alerts);
    }

    /**
     * Create an alert.
     *
     * Requires permission: `alerts.create`.
     *
     * @bodyParam vehicle_id integer optional Vehicle ID. Example: 1
     * @bodyParam alert_type_slug string required Alert type slug. Example: maintenance_due
     * @bodyParam alert_status_slug string optional Alert status slug. Defaults to pending. Example: pending
     * @bodyParam title string required Alert title. Example: Oil change due
     * @bodyParam message string optional Alert message. Example: Schedule oil change.
     * @bodyParam due_date date optional Alert due date. Example: 2026-07-01
     */
    public function store(StoreAlertRequest $request, AlertService $alertService): JsonResponse
    {
        $alert = $alertService->createAlert($request->validated());

        return (new AlertResource($alert->load($this->relationships())))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display an alert.
     *
     * Requires permission: `alerts.view`.
     */
    public function show(Alert $alert): AlertResource
    {
        return new AlertResource($alert->load($this->relationships()));
    }

    /**
     * Mark a pending alert as seen.
     *
     * Requires permission: `alerts.update`.
     */
    public function seen(Alert $alert, AlertService $alertService): AlertResource
    {
        $alert = $alertService->markSeen($alert);

        return new AlertResource($alert->load($this->relationships()));
    }

    /**
     * Mark a pending or seen alert as done.
     *
     * Requires permission: `alerts.update`.
     */
    public function done(Alert $alert, AlertService $alertService): AlertResource
    {
        $alert = $alertService->markDone($alert);

        return new AlertResource($alert->load($this->relationships()));
    }

    /**
     * Ignore a pending or seen alert.
     *
     * Requires permission: `alerts.update`.
     */
    public function ignore(Alert $alert, AlertService $alertService): AlertResource
    {
        $alert = $alertService->ignore($alert);

        return new AlertResource($alert->load($this->relationships()));
    }

    /**
     * Generate maintenance and document expiry alerts.
     *
     * Requires permission: `alerts.create`.
     */
    public function generate(AlertService $alertService): JsonResponse
    {
        $maintenanceAlerts = $alertService->generateMaintenanceAlerts();
        $documentExpiryAlerts = $alertService->generateDocumentExpiryAlerts();

        return response()->json([
            'maintenance_alerts_created' => $maintenanceAlerts,
            'document_expiry_alerts_created' => $documentExpiryAlerts,
            'total_created' => $maintenanceAlerts + $documentExpiryAlerts,
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function relationships(): array
    {
        return ['vehicle', 'alertType', 'alertStatus'];
    }
}
