<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StorePublicContactMessageRequest;
use App\Services\NotificationMailService;
use App\Support\WebsiteContactData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
        NotificationMailService $notificationMailService,
    ): JsonResponse {
        $contact = WebsiteContactData::fromValidated($request->validated());
        $notificationMailService->sendWebsiteContactReceived($contact);

        return response()->json([
            'message' => 'Your message has been sent.',
        ], Response::HTTP_CREATED);
    }
}
