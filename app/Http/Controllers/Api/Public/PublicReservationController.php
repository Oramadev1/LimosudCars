<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StorePublicReservationRequest;
use App\Http\Requests\ReservationAvailabilityRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Location;
use App\Models\PaymentStatus;
use App\Models\Reservation;
use App\Models\ReservationSource;
use App\Models\ReservationStatus;
use App\Models\Vehicle;
use App\Services\AlertService;
use App\Services\CustomerService;
use App\Services\ReservationPricingService;
use App\Services\VehicleAvailabilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * @group Public
 *
 * Public reservation endpoints for website visitors.
 */
class PublicReservationController extends Controller
{
    /**
     * Create a public pending reservation request.
     *
     * @unauthenticated
     *
     * @bodyParam vehicle_id integer required Vehicle ID. Example: 1
     * @bodyParam pickup_location_id integer required Pickup location ID. Example: 1
     * @bodyParam dropoff_location_id integer required Dropoff location ID. Example: 2
     * @bodyParam start_datetime datetime required Reservation start datetime. Example: 2026-07-01 10:00:00
     * @bodyParam end_datetime datetime required Reservation end datetime. Example: 2026-07-05 10:00:00
     * @bodyParam customer.full_name string required Customer full name. Example: Ahmed Dakhla
     * @bodyParam customer.nationality string required Customer nationality. Example: Moroccan
     * @bodyParam customer.phone string required Customer phone number. Example: +212600000000
     * @bodyParam customer.email string optional Customer email. Example: customer@example.com
     * @bodyParam customer.passport_or_cin string optional Passport or CIN. Example: AA123456
     * @bodyParam customer.driving_license_number string optional Driving license number. Example: DL-2026-001
     * @bodyParam customer_notes string optional Customer notes. Example: Airport pickup please.
     */
    public function store(
        StorePublicReservationRequest $request,
        VehicleAvailabilityService $availabilityService,
        ReservationPricingService $pricingService,
        CustomerService $customerService,
        AlertService $alertService,
    ): JsonResponse {
        $reservation = DB::transaction(function () use ($request, $availabilityService, $pricingService, $customerService): Reservation {
            $data = $request->validated();
            $vehicle = Vehicle::findOrFail($data['vehicle_id']);
            $pickupLocation = Location::findOrFail($data['pickup_location_id']);
            $dropoffLocation = Location::findOrFail($data['dropoff_location_id']);

            $availabilityService->preventOverlappingActiveReservations(
                $vehicle,
                $data['start_datetime'],
                $data['end_datetime']
            );

            $customer = $customerService->findOrCreateFromPayload($data['customer']);
            $pricing = $pricingService->calculate($vehicle, $pickupLocation, $dropoffLocation, $data['start_datetime'], $data['end_datetime']);

            return Reservation::create([
                'reservation_number' => $this->generateReservationNumber(),
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'source_id' => ReservationSource::where('slug', 'website')->firstOrFail()->id,
                'status_id' => ReservationStatus::where('slug', 'pending')->firstOrFail()->id,
                'payment_status_id' => PaymentStatus::where('slug', 'unpaid')->firstOrFail()->id,
                'pickup_location_id' => $pickupLocation->id,
                'dropoff_location_id' => $dropoffLocation->id,
                'start_datetime' => $data['start_datetime'],
                'end_datetime' => $data['end_datetime'],
                'customer_notes' => $data['customer_notes'] ?? null,
                ...$pricing,
            ]);
        });

        $alertService->createReservationFollowUpAlert($reservation);

        return (new ReservationResource($reservation->load($this->relationships())))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Check vehicle availability for public visitors.
     *
     * @unauthenticated
     *
     * @bodyParam vehicle_id integer required Vehicle ID to check. Example: 1
     * @bodyParam start_datetime datetime required Start datetime. Example: 2026-07-01 10:00:00
     * @bodyParam end_datetime datetime required End datetime. Example: 2026-07-05 10:00:00
     */
    public function checkAvailability(ReservationAvailabilityRequest $request, VehicleAvailabilityService $availabilityService): JsonResponse
    {
        $data = $request->validated();

        return response()->json([
            'available' => $availabilityService->checkAvailability(
                (int) $data['vehicle_id'],
                $data['start_datetime'],
                $data['end_datetime'],
                $data['ignore_reservation_id'] ?? null
            ),
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function relationships(): array
    {
        return [
            'customer',
            'vehicle.brand',
            'vehicle.category',
            'vehicle.status',
            'vehicle.transmissionType',
            'vehicle.fuelType',
            'source',
            'status',
            'paymentStatus',
            'pickupLocation.locationType',
            'dropoffLocation.locationType',
        ];
    }

    private function generateReservationNumber(): string
    {
        do {
            $number = 'RSV-'.now()->format('Ymd').'-'.random_int(1000, 9999);
        } while (Reservation::where('reservation_number', $number)->exists());

        return $number;
    }
}
