<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadSignedContractRequest;
use App\Http\Resources\ContractResource;
use App\Models\Contract;
use App\Models\Reservation;
use App\Services\ContractPdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @group Contracts
 *
 * Admin contract generation, download, signing, and cancellation endpoints.
 */
class ContractController extends Controller
{
    /**
     * Generate or regenerate a reservation contract.
     *
     * Requires permission: `contracts.generate`.
     */
    public function generate(Reservation $reservation, ContractPdfService $contractPdfService): JsonResponse
    {
        $contract = $contractPdfService->createOrRegenerateContract($reservation, request()->user());

        return (new ContractResource($contract->load(['status', 'generatedBy'])))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Display the reservation contract.
     *
     * Requires permission: `contracts.view`.
     */
    public function showByReservation(Reservation $reservation): ContractResource
    {
        $contract = $reservation->contract()->with(['status', 'generatedBy'])->firstOrFail();

        return new ContractResource($contract);
    }

    /**
     * Download the generated contract PDF from private storage.
     *
     * Requires permission: `contracts.view`.
     */
    public function download(Contract $contract): BinaryFileResponse
    {
        abort_unless($contract->pdf_path && Storage::disk('local')->exists($contract->pdf_path), Response::HTTP_NOT_FOUND);

        return response()->download(
            Storage::disk('local')->path($contract->pdf_path),
            $contract->contract_number.'.pdf'
        );
    }

    /**
     * Upload a signed PDF or mark the contract as signed.
     *
     * Requires permission: `contracts.update`.
     *
     * @bodyParam signed_pdf file optional Signed PDF file. If omitted, the contract is marked signed without upload.
     */
    public function signed(UploadSignedContractRequest $request, Contract $contract, ContractPdfService $contractPdfService): ContractResource
    {
        $contract = $contractPdfService->markSigned($contract, $request->file('signed_pdf'));

        return new ContractResource($contract->load(['status', 'generatedBy']));
    }

    /**
     * Mark the contract as cancelled.
     *
     * Requires permission: `contracts.update`.
     */
    public function cancel(Contract $contract, ContractPdfService $contractPdfService): ContractResource
    {
        $contract = $contractPdfService->cancel($contract);

        return new ContractResource($contract->load(['status', 'generatedBy']));
    }
}
