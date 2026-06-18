<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name')->index();
            $table->string('nationality')->index();
            $table->string('phone')->index();
            $table->string('email')->nullable()->index();
            $table->string('passport_or_cin')->nullable()->index();
            $table->string('driving_license_number')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained('document_types')->restrictOnDelete();
            $table->string('title')->nullable();
            $table->string('file_path');
            $table->date('expires_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'document_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_documents');
        Schema::dropIfExists('customers');
    }
};
