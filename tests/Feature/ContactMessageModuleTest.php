<?php

namespace Tests\Feature;

use App\Mail\WebsiteContactReceived;
use App\Models\Alert;
use App\Models\AlertStatus;
use App\Models\AlertType;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactMessageModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_contact_message_creates_record_and_pending_alert(): void
    {
        config(['mail.default' => 'log']);
        Mail::fake();
        $this->seed();

        $this->postJson('/api/public/contact-messages', [
            'name' => 'Ahmed Dakhla',
            'email' => 'ahmed@example.com',
            'phone' => '+212600000000',
            'message' => 'I would like to rent a car next week.',
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Ahmed Dakhla')
            ->assertJsonPath('data.email', 'ahmed@example.com')
            ->assertJsonPath('data.is_read', false);

        $message = ContactMessage::firstOrFail();

        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'name' => 'Ahmed Dakhla',
            'email' => 'ahmed@example.com',
        ]);

        $this->assertDatabaseHas('alerts', [
            'contact_message_id' => $message->id,
            'alert_type_id' => AlertType::where('slug', 'website_contact')->value('id'),
            'alert_status_id' => AlertStatus::where('slug', 'pending')->value('id'),
        ]);

        Mail::assertNothingSent();
    }

    public function test_public_contact_message_sends_notification_email_when_smtp_configured(): void
    {
        config([
            'mail.default' => 'smtp',
            'limosud.notifications.email' => 'owner@example.com',
            'limosud.notifications.send_contact_messages' => true,
        ]);

        Mail::fake();
        $this->seed();

        $this->postJson('/api/public/contact-messages', [
            'name' => 'Ahmed Dakhla',
            'email' => 'ahmed@example.com',
            'phone' => '+212600000000',
            'message' => 'I would like to rent a car next week.',
        ])->assertCreated();

        Mail::assertSent(WebsiteContactReceived::class, function (WebsiteContactReceived $mail): bool {
            return $mail->hasTo('owner@example.com');
        });
    }

    public function test_admin_can_list_and_mark_contact_message_as_read(): void
    {
        $this->seed();
        $token = $this->adminToken();

        $message = ContactMessage::create([
            'name' => 'Sara',
            'email' => 'sara@example.com',
            'phone' => null,
            'message' => 'Need airport pickup.',
        ]);

        app(\App\Services\AlertService::class)->createContactMessageAlert($message);

        $this->withToken($token)
            ->getJson('/api/admin/contact-messages')
            ->assertOk()
            ->assertJsonPath('data.0.id', $message->id)
            ->assertJsonPath('data.0.is_read', false);

        $this->withToken($token)
            ->patchJson("/api/admin/contact-messages/{$message->id}/read")
            ->assertOk()
            ->assertJsonPath('data.is_read', true);

        $this->assertNotNull($message->fresh()->read_at);
        $this->assertSame(0, Alert::query()
            ->where('contact_message_id', $message->id)
            ->where('alert_status_id', AlertStatus::where('slug', 'pending')->value('id'))
            ->count());
    }
}
