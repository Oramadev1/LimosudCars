<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StorePublicContactMessageRequest;
use App\Http\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use App\Services\AlertService;
use App\Services\NotificationMailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * @group Public
 *
 * Public website contact form endpoints.
 */
class PublicContactMessageController extends Controller
{
    /**
     * Submit a contact message from the public website.
     *
     * @unauthenticated
     *
     * @bodyParam name string required Sender name. Example: Ahmed Dakhla
     * @bodyParam email string required Sender email. Example: customer@example.com
     * @bodyParam phone string optional Sender phone. Example: +212600000000
     * @bodyParam message string required Message body. Example: I need a quote for a weekly rental.
     */
    public function store(
        StorePublicContactMessageRequest $request,
        AlertService $alertService,
        NotificationMailService $notificationMailService,
    ): JsonResponse {
        $message = DB::transaction(function () use ($request, $alertService): ContactMessage {
            $contactMessage = ContactMessage::create($request->validated());
            $alertService->createContactMessageAlert($contactMessage);

            return $contactMessage;
        });

        $notificationMailService->sendWebsiteContactReceived($message);

        return (new ContactMessageResource($message))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
