<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use App\Services\AlertService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Contact Messages
 *
 * Admin contact messages submitted from the public website.
 */
class ContactMessageController extends Controller
{
    /**
     * List contact messages.
     *
     * Requires permission: `contact_messages.view`.
     */
    public function index(): AnonymousResourceCollection
    {
        $messages = ContactMessage::query()
            ->latest()
            ->paginate(15);

        return ContactMessageResource::collection($messages);
    }

    /**
     * Show a contact message and mark it as read.
     *
     * Requires permission: `contact_messages.view`.
     */
    public function show(ContactMessage $contactMessage, AlertService $alertService): ContactMessageResource
    {
        if (! $contactMessage->isRead()) {
            $contactMessage->update(['read_at' => now()]);
            $alertService->resolveContactMessageAlert($contactMessage);
            $contactMessage->refresh();
        }

        return new ContactMessageResource($contactMessage);
    }

    /**
     * Mark a contact message as read.
     *
     * Requires permission: `contact_messages.update`.
     */
    public function markRead(ContactMessage $contactMessage, AlertService $alertService): ContactMessageResource
    {
        if (! $contactMessage->isRead()) {
            $contactMessage->update(['read_at' => now()]);
            $alertService->resolveContactMessageAlert($contactMessage);
            $contactMessage->refresh();
        }

        return new ContactMessageResource($contactMessage);
    }

    /**
     * Delete a contact message.
     *
     * Requires permission: `contact_messages.delete`.
     */
    public function destroy(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
