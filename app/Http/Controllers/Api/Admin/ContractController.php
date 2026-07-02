<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GenerateContractRequest;
use App\Http\Requests\Admin\UploadSignedContractRequest;
use App\Http\Resources\ContractResource;
use App\Models\Contract;
use App\Models\Reservation;
use App\Services\ContractFormDataService;
use App\Services\ContractPdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ContractController extends Controller
{
    public function form(Reservation $reservation, ContractFormDataService $contractFormDataService): JsonResponse
    {
        return response()->json([
            'data' => $contractFormDataService->build($reservation),
        ]);
    }

    public function generate(
        GenerateContractRequest $request,
        Reservation $reservation,
        ContractPdfService $contractPdfService,
    ): JsonResponse {
        $validated = $request->validated();

        $contract = $contractPdfService->createOrRegenerateContract(
            $reservation,
            $request->user(),
            $validated['details'] ?? [],
            $validated['contract_series'] ?? null,
        );

        return (new ContractResource($contract->load(['status', 'generatedBy'])))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function showByReservation(Reservation $reservation): ContractResource
    {
        $contract = $reservation->contract()->with(['status', 'generatedBy'])->firstOrFail();

        return new ContractResource($contract);
    }

    public function download(Contract $contract): BinaryFileResponse
    {
        abort_unless($contract->pdf_path && Storage::disk('local')->exists($contract->pdf_path), Response::HTTP_NOT_FOUND);

        return response()->download(
            Storage::disk('local')->path($contract->pdf_path),
            $contract->contract_number.'.pdf'
        );
    }

    public function signed(UploadSignedContractRequest $request, Contract $contract, ContractPdfService $contractPdfService): ContractResource
    {
        $contract = $contractPdfService->markSigned($contract, $request->file('signed_pdf'));

        return new ContractResource($contract->load(['status', 'generatedBy']));
    }

    public function cancel(Contract $contract, ContractPdfService $contractPdfService): ContractResource
    {
        $contract = $contractPdfService->cancel($contract);

        return new ContractResource($contract->load(['status', 'generatedBy']));
    }
}
