<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerDocumentRequest;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Http\Resources\CustomerDocumentResource;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\DocumentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @group Customers
 *
 * Admin customer profile and customer document endpoints. Requires the matching `customers.*` permission listed on each endpoint.
 */
class CustomerController extends Controller
{
    /**
     * List customers for the admin dashboard.
     *
     * Requires permission: `customers.view`.
     */
    public function index(): AnonymousResourceCollection
    {
        $customers = Customer::query()
            ->latest()
            ->paginate(15);

        return CustomerResource::collection($customers);
    }

    /**
     * Store a new customer.
     *
     * Requires permission: `customers.create`.
     *
     * @bodyParam full_name string required Customer full name. Example: Ahmed Dakhla
     * @bodyParam nationality string required Customer nationality. Example: Moroccan
     * @bodyParam phone string required Customer phone number. Example: +212600000000
     * @bodyParam email string optional Customer email. Example: customer@example.com
     * @bodyParam passport_or_cin string optional Passport or CIN. Example: AA123456
     * @bodyParam driving_license_number string optional Driving license number. Example: DL-2026-001
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = Customer::create($request->validated());

        return (new CustomerResource($customer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display a customer.
     *
     * Requires permission: `customers.view`.
     */
    public function show(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer->load('documents.documentType'));
    }

    /**
     * Update a customer.
     *
     * Requires permission: `customers.update`.
     *
     * @bodyParam phone string optional Customer phone number. Example: +212611111111
     * @bodyParam email string optional Customer email. Example: updated@example.com
     * @bodyParam driving_license_number string optional Driving license number. Example: DL-2026-002
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): CustomerResource
    {
        $customer->update($request->validated());

        return new CustomerResource($customer->load('documents.documentType'));
    }

    /**
     * Soft delete a customer.
     *
     * Requires permission: `customers.delete`.
     */
    public function destroy(Customer $customer): Response
    {
        $customer->delete();

        return response()->noContent();
    }

    /**
     * Upload a customer document and link it to a document type lookup.
     *
     * Requires permission: `customers.update`.
     *
     * @bodyParam document_type_slug string required Document type slug. Example: passport
     * @bodyParam title string optional Document title. Example: Passport scan
     * @bodyParam file file required PDF or image document file.
     * @bodyParam expires_at date optional Expiry date. Example: 2028-12-31
     */
    public function storeDocument(StoreCustomerDocumentRequest $request, Customer $customer): JsonResponse
    {
        $document = DB::transaction(function () use ($request, $customer): CustomerDocument {
            $data = $request->validated();
            $documentType = DocumentType::where('slug', $data['document_type_slug'])->firstOrFail();
            $path = $request->file('file')->store("customer-documents/{$customer->id}", 'public');

            return $customer->documents()->create([
                'document_type_id' => $documentType->id,
                'title' => $data['title'] ?? null,
                'file_path' => $path,
                'expires_at' => $data['expires_at'] ?? null,
            ]);
        });

        return (new CustomerDocumentResource($document->load('documentType')))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Delete a stored customer document file and soft delete the record.
     *
     * Requires permission: `customers.update`.
     */
    public function destroyDocument(CustomerDocument $document): Response
    {
        DB::transaction(function () use ($document): void {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();
        });

        return response()->noContent();
    }
}
