<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\ContractStatus;
use App\Models\PaymentStatus;
use App\Models\Reservation;
use App\Models\User;
use App\Support\ContractDetails;
use App\Support\ContractViewData;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ContractPdfService
{
    public function generateContractNumber(): string
    {
        do {
            $number = 'CTR-'.now()->format('Ymd').'-'.random_int(1000, 9999);
        } while (Contract::where('contract_number', $number)->exists());

        return $number;
    }

  /**
     * @param  array<string, mixed>  $details
     */
    public function generatePdf(
        Reservation $reservation,
        string $contractNumber,
        array $details = [],
        string $contractSeries = 'A',
    ): string {
        $reservation->loadMissing([
            'customer',
            'vehicle.brand',
            'vehicle.category',
            'vehicle.transmissionType',
            'vehicle.fuelType',
            'source',
            'status',
            'paymentStatus',
            'pickupLocation',
            'dropoffLocation',
            'payments.paymentMethod',
            'payments.paymentStatus',
        ]);

        $html = $this->contractHtml($reservation, $contractNumber, $details, $contractSeries);
        $path = 'contracts/'.$contractNumber.'.pdf';

        Storage::disk('local')->put($path, app(ContractMpdfRenderer::class)->render($html));

        return $path;
    }

    /**
     * @param  array<string, mixed>  $inputDetails
     */
    public function createOrRegenerateContract(
        Reservation $reservation,
        ?User $user = null,
        array $inputDetails = [],
        ?string $contractSeries = null,
    ): Contract {
        return DB::transaction(function () use ($reservation, $user, $inputDetails, $contractSeries): Contract {
            $reservation = Reservation::whereKey($reservation->id)->lockForUpdate()->firstOrFail();
            $reservation->loadMissing('status', 'customer', 'vehicle');

            $this->ensureReservationCanGenerateContract($reservation);

            $contract = Contract::withTrashed()->where('reservation_id', $reservation->id)->first();
            $details = ContractDetails::merge(
                ContractDetails::fromReservation($reservation, $contract),
                $inputDetails,
            );

            ContractDetails::persistRelatedRecords($reservation, $details);

            $contractNumber = $contract?->contract_number ?? $this->generateContractNumber();
            $series = $contractSeries ?? $contract?->contract_series ?? 'A';
            $pdfPath = $this->generatePdf($reservation, $contractNumber, $details, $series);

            if ($contract?->trashed()) {
                $contract->restore();
            }

            $payload = [
                'status_id' => $this->contractStatusId('generated'),
                'contract_series' => $series,
                'pdf_path' => $pdfPath,
                'details' => $details,
                'generated_by' => $user?->id,
                'generated_at' => now(),
            ];

            if ($contract) {
                if ($contract->pdf_path && $contract->pdf_path !== $pdfPath) {
                    Storage::disk('local')->delete($contract->pdf_path);
                }

                $contract->update($payload);

                return $contract;
            }

            return Contract::create([
                'reservation_id' => $reservation->id,
                'contract_number' => $contractNumber,
                ...$payload,
            ]);
        });
    }

    public function markSigned(Contract $contract, ?UploadedFile $signedPdfFile = null): Contract
    {
        return DB::transaction(function () use ($contract, $signedPdfFile): Contract {
            $contract = Contract::whereKey($contract->id)->lockForUpdate()->firstOrFail();
            $contract->loadMissing('status');

            if ($contract->status?->slug === 'signed') {
                throw ValidationException::withMessages([
                    'contract' => 'This contract is already marked as signed.',
                ]);
            }

            if ($contract->status?->slug === 'cancelled') {
                throw ValidationException::withMessages([
                    'contract' => 'Cancelled contracts cannot be marked as signed.',
                ]);
            }

            $signedPdfPath = $contract->signed_pdf_path;

            if ($signedPdfFile) {
                if ($signedPdfPath) {
                    Storage::disk('local')->delete($signedPdfPath);
                }

                $signedPdfPath = $signedPdfFile->storeAs(
                    'contracts/signed',
                    $contract->contract_number.'-signed.'.$signedPdfFile->getClientOriginalExtension(),
                    'local'
                );
            }

            $contract->update([
                'status_id' => $this->contractStatusId('signed'),
                'signed_pdf_path' => $signedPdfPath,
                'signed_at' => now(),
            ]);

            return $contract;
        });
    }

    public function cancel(Contract $contract): Contract
    {
        return DB::transaction(function () use ($contract): Contract {
            $contract = Contract::whereKey($contract->id)->lockForUpdate()->firstOrFail();
            $contract->loadMissing('status');

            if ($contract->status?->slug === 'cancelled') {
                throw ValidationException::withMessages([
                    'contract' => 'This contract is already cancelled.',
                ]);
            }

            if ($contract->status?->slug === 'signed') {
                throw ValidationException::withMessages([
                    'contract' => 'Signed contracts cannot be cancelled.',
                ]);
            }

            $contract->update([
                'status_id' => $this->contractStatusId('cancelled'),
            ]);

            return $contract;
        });
    }

    /**
     * @throws ValidationException
     */
    private function ensureReservationCanGenerateContract(Reservation $reservation): void
    {
        if (! in_array($reservation->status?->slug, ['confirmed', 'in_progress', 'completed'], true)) {
            throw ValidationException::withMessages([
                'reservation_id' => 'Contracts can only be generated for confirmed, in-progress, or completed reservations.',
            ]);
        }
    }

    private function contractStatusId(string $slug): int
    {
        return ContractStatus::where('slug', $slug)->firstOrFail()->id;
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function contractHtml(
        Reservation $reservation,
        string $contractNumber,
        array $details,
        string $contractSeries,
    ): string {
        $paidStatusId = PaymentStatus::where('slug', 'paid')->value('id');
        $paidAmount = (float) $reservation->payments
            ->where('payment_status_id', $paidStatusId)
            ->sum('amount');
        $remainingAmount = max(0, (float) $reservation->total_price - $paidAmount);
        $logoPath = public_path('images/logo.jpg');
        $logoData = is_readable($logoPath) ? base64_encode((string) file_get_contents($logoPath)) : null;

        return view('pdf.contract', ContractViewData::fromReservation(
            $reservation,
            $contractNumber,
            $paidAmount,
            $remainingAmount,
            $logoData,
            $details,
            $contractSeries,
        ))->render();
    }
}
