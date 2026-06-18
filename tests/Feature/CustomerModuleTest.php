<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\DocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_list_update_show_and_delete_customer(): void
    {
        $this->seed();

        $token = $this->adminToken();

        $createResponse = $this->withToken($token)->postJson('/api/admin/customers', [
            'full_name' => 'Fatima El Amrani',
            'nationality' => 'Moroccan',
            'phone' => '+212600000001',
            'email' => 'fatima@example.com',
            'passport_or_cin' => 'AA123456',
            'driving_license_number' => 'DL123456',
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.full_name', 'Fatima El Amrani')
            ->assertJsonPath('data.email', 'fatima@example.com');

        $customer = Customer::where('email', 'fatima@example.com')->firstOrFail();

        $this->withToken($token)
            ->getJson('/api/admin/customers')
            ->assertOk()
            ->assertJsonPath('data.0.email', 'fatima@example.com');

        $this->withToken($token)
            ->patchJson("/api/admin/customers/{$customer->id}", [
                'phone' => '+212600000002',
                'passport_or_cin' => null,
            ])
            ->assertOk()
            ->assertJsonPath('data.phone', '+212600000002')
            ->assertJsonPath('data.passport_or_cin', null);

        $this->withToken($token)
            ->getJson("/api/admin/customers/{$customer->id}")
            ->assertOk()
            ->assertJsonPath('data.full_name', 'Fatima El Amrani');

        $this->withToken($token)
            ->deleteJson("/api/admin/customers/{$customer->id}")
            ->assertNoContent();

        $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    }

    public function test_admin_can_upload_and_delete_customer_document(): void
    {
        Storage::fake('public');
        $this->seed();

        $token = $this->adminToken();
        $customer = Customer::factory()->create();

        $uploadResponse = $this->withToken($token)->postJson("/api/admin/customers/{$customer->id}/documents", [
            'document_type_slug' => 'passport',
            'title' => 'Passport scan',
            'file' => UploadedFile::fake()->create('passport.pdf', 128, 'application/pdf'),
            'expires_at' => now()->addYears(5)->toDateString(),
        ]);

        $uploadResponse
            ->assertCreated()
            ->assertJsonPath('data.document_type.slug', 'passport')
            ->assertJsonPath('data.title', 'Passport scan');

        $document = CustomerDocument::firstOrFail();

        $this->assertSame($customer->id, $document->customer_id);
        $this->assertSame(DocumentType::where('slug', 'passport')->value('id'), $document->document_type_id);
        Storage::disk('public')->assertExists($document->file_path);

        $this->withToken($token)
            ->getJson("/api/admin/customers/{$customer->id}")
            ->assertOk()
            ->assertJsonPath('data.documents.0.document_type.slug', 'passport');

        $this->withToken($token)
            ->deleteJson("/api/admin/customer-documents/{$document->id}")
            ->assertNoContent();

        $this->assertSoftDeleted('customer_documents', ['id' => $document->id]);
        Storage::disk('public')->assertMissing($document->file_path);
    }

    public function test_customer_endpoints_require_authentication(): void
    {
        $this->getJson('/api/admin/customers')->assertUnauthorized();
    }

    private function adminToken(): string
    {
        $response = $this->postJson('/api/admin/auth/login', [
            'email' => env('ADMIN_EMAIL', 'admin@limosudcars.local'),
            'password' => env('ADMIN_PASSWORD', 'password'),
        ]);

        $response->assertOk();

        return $response->json('access_token');
    }
}
