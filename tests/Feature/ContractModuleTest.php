<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\ContractStatus;
use App\Models\Customer;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\Reservation;
use App\Models\ReservationSource;
use App\Models\ReservationStatus;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContractModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_generated_contract_pdf_uses_limosud_template_sections(): void
    {
        Storage::fake('local');
        $this->seed();

        $reservation = $this->reservation('confirmed');
        $this->paidPayment($reservation, 300);

        $this->withToken($this->adminToken())
            ->postJson("/api/admin/reservations/{$reservation->id}/contract/generate")
            ->assertOk();

        $contract = Contract::firstOrFail();
        $pdfContents = Storage::disk('local')->get($contract->pdf_path);

        $this->assertNotEmpty($pdfContents);
        $this->assertStringStartsWith('%PDF', $pdfContents);
    }

    public function test_contract_html_includes_limosud_form_sections(): void
    {
        $this->seed();

        $reservation = $this->reservation('confirmed');
        $reservation->loadMissing([
            'customer',
            'vehicle.brand',
            'pickupLocation',
            'dropoffLocation',
            'payments.paymentMethod',
        ]);

        $html = view('pdf.contract', \App\Support\ContractViewData::fromReservation(
            $reservation,
            'CTR-20260615-1234',
            300,
            1200,
            null,
        ))->render();

        $this->assertStringContainsString('LIMOSUD CARS', $html);
        $this->assertStringContainsString('Locataire', $html);
        $this->assertStringContainsString('المستأجر', $html);
        $this->assertStringContainsString('conducteur supplémentaire', $html);
        $this->assertStringContainsString('Prix total', $html);
        $this->assertStringContainsString('5 000 dirhams', $html);
        $this->assertStringContainsString('Le locataire', $html);
        $this->assertStringContainsString('Le loueur', $html);
        $this->assertStringContainsString('État voiture', $html);
        $this->assertStringContainsString('Papier de Véhicule', $html);
    }

    public function test_cannot_generate_contract_for_pending_reservation(): void
    {
        Storage::fake('local');
        $this->seed();

        $reservation = $this->reservation('pending');

        $this->withToken($this->adminToken())
            ->postJson("/api/admin/reservations/{$reservation->id}/contract/generate")
            ->assertUnprocessable();
    }

    public function test_can_generate_contract_for_confirmed_reservation(): void
    {
        Storage::fake('local');
        $this->seed();

        $reservation = $this->reservation('confirmed');
        $this->paidPayment($reservation, 300);

        $response = $this->withToken($this->adminToken())
            ->postJson("/api/admin/reservations/{$reservation->id}/contract/generate");

        $response
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'generated')
            ->assertJsonPath('data.has_pdf', true)
            ->assertJsonMissingPath('data.pdf_path')
            ->assertJsonMissingPath('data.signed_pdf_path');

        $contract = Contract::firstOrFail();

        $this->assertSame(ContractStatus::where('slug', 'generated')->value('id'), $contract->status_id);
        Storage::disk('local')->assertExists($contract->pdf_path);
    }

    public function test_regeneration_keeps_same_contract_number(): void
    {
        Storage::fake('local');
        $this->seed();

        $reservation = $this->reservation('confirmed');
        $token = $this->adminToken();

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$reservation->id}/contract/generate")
            ->assertOk();

        $contract = Contract::firstOrFail();
        $contractNumber = $contract->contract_number;

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$reservation->id}/contract/generate")
            ->assertOk()
            ->assertJsonPath('data.contract_number', $contractNumber);

        $this->assertSame($contractNumber, $contract->refresh()->contract_number);
        $this->assertSame(1, Contract::count());
    }

    public function test_signed_upload_marks_contract_signed(): void
    {
        Storage::fake('local');
        $this->seed();

        $reservation = $this->reservation('confirmed');
        $token = $this->adminToken();

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$reservation->id}/contract/generate")
            ->assertOk();

        $contract = Contract::firstOrFail();

        $this->withToken($token)
            ->postJson("/api/admin/contracts/{$contract->id}/signed", [
                'signed_pdf' => UploadedFile::fake()->create('signed-contract.pdf', 128, 'application/pdf'),
            ])
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'signed')
            ->assertJsonPath('data.has_signed_pdf', true);

        $contract->refresh();

        $this->assertNotNull($contract->signed_at);
        $this->assertSame(ContractStatus::where('slug', 'signed')->value('id'), $contract->status_id);
        Storage::disk('local')->assertExists($contract->signed_pdf_path);

        $this->withToken($token)
            ->postJson("/api/admin/contracts/{$contract->id}/signed")
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['contract']);
    }

    public function test_download_endpoint_returns_generated_pdf(): void
    {
        Storage::fake('local');
        $this->seed();

        $reservation = $this->reservation('confirmed');
        $token = $this->adminToken();

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$reservation->id}/contract/generate")
            ->assertOk();

        $contract = Contract::firstOrFail();

        $this->withToken($token)
            ->get("/api/admin/contracts/{$contract->id}/download")
            ->assertOk();
    }

    public function test_cancelled_and_rejected_reservations_cannot_generate_contract(): void
    {
        Storage::fake('local');
        $this->seed();
        $token = $this->adminToken();

        foreach (['cancelled', 'rejected'] as $statusSlug) {
            $reservation = $this->reservation($statusSlug);

            $this->withToken($token)
                ->postJson("/api/admin/reservations/{$reservation->id}/contract/generate")
                ->assertUnprocessable();
        }
    }

    public function test_contract_can_be_cancelled_without_deleting_record(): void
    {
        Storage::fake('local');
        $this->seed();

        $reservation = $this->reservation('confirmed');
        $token = $this->adminToken();

        $this->withToken($token)
            ->postJson("/api/admin/reservations/{$reservation->id}/contract/generate")
            ->assertOk();

        $contract = Contract::firstOrFail();

        $this->withToken($token)
            ->postJson("/api/admin/contracts/{$contract->id}/cancel")
            ->assertOk()
            ->assertJsonPath('data.status.slug', 'cancelled');

        $this->assertDatabaseHas('contracts', [
            'id' => $contract->id,
            'status_id' => ContractStatus::where('slug', 'cancelled')->value('id'),
        ]);
    }

    public function test_contract_form_endpoint_returns_prefilled_data(): void
    {
        $this->seed();

        $reservation = $this->reservation('confirmed');

        $this->withToken($this->adminToken())
            ->getJson("/api/admin/reservations/{$reservation->id}/contract/form")
            ->assertOk()
            ->assertJsonPath('data.reservation_id', $reservation->id)
            ->assertJsonPath('data.can_generate', true)
            ->assertJsonStructure([
                'data' => [
                    'auto' => ['customer', 'vehicle', 'rental', 'payment'],
                    'details' => ['customer', 'vehicle', 'equipment', 'documents'],
                    'missing_fields',
                ],
            ]);
    }

    public function test_generate_contract_accepts_details_payload(): void
    {
        Storage::fake('local');
        $this->seed();

        $reservation = $this->reservation('confirmed');

        $this->withToken($this->adminToken())
            ->postJson("/api/admin/reservations/{$reservation->id}/contract/generate", [
                'contract_series' => 'B',
                'details' => [
                    'customer' => ['address' => 'Hay Al Qods N10'],
                    'equipment' => ['jack' => true, 'warning_triangle' => true],
                    'insurance' => ['type' => 'premium', 'deductible' => 4000],
                ],
            ])
            ->assertOk()
            ->assertJsonPath('data.contract_series', 'B');

        $contract = Contract::firstOrFail();
        $this->assertSame('B', $contract->contract_series);
        $this->assertSame('Hay Al Qods N10', $contract->details['customer']['address']);
        $this->assertTrue($contract->details['equipment']['jack']);

        $reservation->loadMissing(['customer', 'vehicle.brand', 'vehicle.category', 'pickupLocation', 'dropoffLocation', 'payments.paymentMethod']);
        $html = view('pdf.contract', \App\Support\ContractViewData::fromReservation(
            $reservation,
            $contract->contract_number,
            0,
            1500,
            null,
            $contract->details,
            $contract->contract_series,
        ))->render();

        $this->assertStringContainsString('Hay Al Qods N10', $html);
    }

    public function test_contract_html_shows_labeled_additional_driver_fields(): void
    {
        $this->seed();

        $reservation = $this->reservation('confirmed');
        $reservation->loadMissing([
            'customer',
            'vehicle.brand',
            'vehicle.category',
            'pickupLocation',
            'dropoffLocation',
            'payments.paymentMethod',
        ]);

        $details = [
            'additional_driver' => [
                'enabled' => true,
                'full_name' => 'Ahmed Benali',
                'address' => 'Rue Hassan II, Casablanca',
                'nationality' => 'Moroccan',
                'phone' => '+212600111222',
                'passport_or_cin' => 'AB123456',
                'driving_license_number' => 'DL-998877',
                'license_issued_at' => '2020-01-15',
                'license_expires_at' => '2030-01-15',
            ],
        ];

        $html = view('pdf.contract', \App\Support\ContractViewData::fromReservation(
            $reservation,
            'CTR-TEST-0001',
            0,
            1500,
            null,
            $details,
        ))->render();

        $this->assertStringContainsString('NOM ET PRENOM', $html);
        $this->assertStringContainsString('Ahmed Benali', $html);
        $this->assertStringContainsString('Permis de conduire', $html);
        $this->assertStringContainsString('DL-998877', $html);
        $this->assertStringContainsString('C.I.N N° ou passeport', $html);
        $this->assertStringContainsString('AB123456', $html);
        $this->assertStringContainsString('الاسم والنسب', $html);
    }

    private function reservation(string $statusSlug): Reservation
    {
        [$pickupLocation, $dropoffLocation] = $this->locations();

        return Reservation::create([
            'reservation_number' => 'RSV-CTR-'.fake()->unique()->numerify('####'),
            'customer_id' => Customer::factory()->create([
                'full_name' => 'Contract Customer',
                'nationality' => 'Moroccan',
                'phone' => '+212600000333',
            ])->id,
            'vehicle_id' => $this->vehicle()->id,
            'source_id' => ReservationSource::where('slug', 'website')->value('id'),
            'status_id' => ReservationStatus::where('slug', $statusSlug)->value('id'),
            'payment_status_id' => PaymentStatus::where('slug', 'partial_paid')->value('id'),
            'pickup_location_id' => $pickupLocation->id,
            'dropoff_location_id' => $dropoffLocation->id,
            'start_datetime' => now()->addDays(5)->setTime(10, 0),
            'end_datetime' => now()->addDays(8)->setTime(10, 0),
            'total_days' => 3,
            'price_per_day' => 300,
            'delivery_fee' => 100,
            'deposit_amount' => 500,
            'total_price' => 1500,
        ]);
    }

    private function paidPayment(Reservation $reservation, float $amount): Payment
    {
        return Payment::create([
            'reservation_id' => $reservation->id,
            'payment_method_id' => PaymentMethod::where('slug', 'cash')->value('id'),
            'payment_type_id' => PaymentType::where('slug', 'rental_payment')->value('id'),
            'payment_status_id' => PaymentStatus::where('slug', 'paid')->value('id'),
            'amount' => $amount,
            'payment_date' => now()->toDateString(),
        ]);
    }

    private function vehicle(): Vehicle
    {
        return Vehicle::factory()->create();
    }

    /**
     * @return array{0: Location, 1: Location}
     */
    private function locations(): array
    {
        $agencyTypeId = LocationType::where('slug', 'agency')->value('id');
        $airportTypeId = LocationType::where('slug', 'airport')->value('id');

        return [
            Location::firstOrCreate(
                ['slug' => 'contract-agency'],
                [
                    'location_type_id' => $agencyTypeId,
                    'name' => 'Contract Agency',
                    'address' => 'Dakhla Center',
                    'delivery_fee' => 0,
                    'is_active' => true,
                ]
            ),
            Location::firstOrCreate(
                ['slug' => 'contract-airport'],
                [
                    'location_type_id' => $airportTypeId,
                    'name' => 'Contract Airport',
                    'address' => 'Dakhla Airport',
                    'delivery_fee' => 100,
                    'is_active' => true,
                ]
            ),
        ];
    }

}
