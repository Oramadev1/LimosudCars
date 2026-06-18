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
        Schema::create('contracts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('reservation_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('status_id')->constrained('contract_statuses')->restrictOnDelete();
            $table->string('contract_number')->unique();
            $table->string('pdf_path')->nullable();
            $table->string('signed_pdf_path')->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('generated_at')->nullable()->index();
            $table->timestamp('signed_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status_id', 'generated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
