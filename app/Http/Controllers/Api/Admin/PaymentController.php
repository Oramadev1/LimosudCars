<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentRequest;
use App\Http\Requests\Admin\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\Reservation;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Payments
 *
 * Admin payment endpoints. Requires `payments.view` or `payments.manage` as listed on each endpoint.
 */
class PaymentController extends Controller
{
    /**
     * List payments for the admin dashboard.
     *
     * Requires permission: `payments.view`.
     */
    public function index(): AnonymousResourceCollection
    {
        $payments = Payment::query()
            ->with($this->relationships())
            ->latest()
            ->paginate(15);

        return PaymentResource::collection($payments);
    }

    /**
     * Store a new payment and recalculate reservation payment status.
     *
     * Requires permission: `payments.manage`.
     *
     * @bodyParam reservation_id integer required Reservation ID. Example: 1
     * @bodyParam payment_method_slug string required Payment method slug. Example: cash
     * @bodyParam payment_type_slug string required Payment type slug. Example: rental_payment
     * @bodyParam payment_status_slug string required Payment status slug. Example: paid
     * @bodyParam amount number required Payment amount. Example: 300
     * @bodyParam payment_date date required Payment date. Example: 2026-06-10
     * @bodyParam paid_by_customer_name string optional Name of the paying customer. Example: Ahmed Dakhla
     * @bodyParam reference string optional External reference. Example: PAY-001
     * @bodyParam notes string optional Internal payment notes. Example: Advance payment.
     */
    public function store(StorePaymentRequest $request, PaymentService $paymentService): JsonResponse
    {
        $payment = $paymentService->createPayment($request->validated(), $request->user());

        return (new PaymentResource($payment->load($this->relationships())))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display a payment.
     *
     * Requires permission: `payments.view`.
     */
    public function show(Payment $payment): PaymentResource
    {
        return new PaymentResource($payment->load($this->relationships()));
    }

    /**
     * Update a payment and recalculate reservation payment status.
     *
     * Requires permission: `payments.manage`.
     *
     * @bodyParam payment_status_slug string optional Payment status slug. Example: paid
     * @bodyParam amount number optional Payment amount. Example: 1000
     * @bodyParam reference string optional External reference. Example: FULL-001
     */
    public function update(UpdatePaymentRequest $request, Payment $payment, PaymentService $paymentService): PaymentResource
    {
        $payment = $paymentService->updatePayment($payment, $request->validated());

        return new PaymentResource($payment->load($this->relationships()));
    }

    /**
     * Safely cancel a payment without deleting the financial record.
     *
     * Requires permission: `payments.manage`.
     */
    public function cancel(Payment $payment, PaymentService $paymentService): PaymentResource
    {
        $payment = $paymentService->cancelPayment($payment);

        return new PaymentResource($payment->load($this->relationships()));
    }

    /**
     * Return calculated payment totals for a reservation.
     *
     * Requires permission: `payments.view`.
     */
    public function summary(Reservation $reservation): JsonResponse
    {
        $paidAmount = (float) $reservation->payments()
            ->where('payment_status_id', PaymentStatus::where('slug', 'paid')->firstOrFail()->id)
            ->sum('amount');

        $reservation->load('paymentStatus');

        return response()->json([
            'reservation_id' => $reservation->id,
            'reservation_number' => $reservation->reservation_number,
            'total_price' => (float) $reservation->total_price,
            'paid_amount' => round($paidAmount, 2),
            'remaining_amount' => round(max(0, (float) $reservation->total_price - $paidAmount), 2),
            'payment_status' => [
                'id' => $reservation->paymentStatus->id,
                'name' => $reservation->paymentStatus->name,
                'slug' => $reservation->paymentStatus->slug,
            ],
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function relationships(): array
    {
        return [
            'reservation',
            'paymentMethod',
            'paymentType',
            'paymentStatus',
            'createdBy',
        ];
    }
}
