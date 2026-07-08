<?php

namespace Tests\Feature;

use App\Mail\WebsiteContactReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactMessageModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_contact_message_sends_email_when_smtp_configured(): void
    {
        config([
            'mail.default' => 'smtp',
            'limosud.notifications.email' => 'owner@example.com',
            'limosud.notifications.send_contact_messages' => true,
        ]);

        Mail::fake();

        $this->postJson('/api/public/contact-messages', [
            'name' => 'Ahmed Dakhla',
            'email' => 'ahmed@example.com',
            'phone' => '+212600000000',
            'message' => 'I would like to rent a car next week.',
        ])
            ->assertCreated()
            ->assertJsonPath('message', 'Your message has been sent.');

        Mail::assertSent(WebsiteContactReceived::class, function (WebsiteContactReceived $mail): bool {
            return $mail->hasTo('owner@example.com')
                && $mail->contact->email === 'ahmed@example.com';
        });
    }

    public function test_public_contact_message_skips_email_when_mailer_is_log(): void
    {
        config(['mail.default' => 'log']);

        Mail::fake();

        $this->postJson('/api/public/contact-messages', [
            'name' => 'Ahmed Dakhla',
            'email' => 'ahmed@example.com',
            'message' => 'Hello',
        ])
            ->assertCreated()
            ->assertJsonPath('message', 'Your message has been sent.');

        Mail::assertNothingSent();
    }
}
