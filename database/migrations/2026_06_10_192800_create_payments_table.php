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
        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->restrictOnDelete();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->restrictOnDelete();
            $table->foreignId('payment_type_id')->constrained('payment_types')->restrictOnDelete();
            $table->foreignId('payment_status_id')->constrained('payment_statuses')->restrictOnDelete();
            $table->decimal('amount', 10, 2);
            $table->date('payment_date')->index();
            $table->string('paid_by_customer_name')->nullable();
            $table->string('reference')->nullable()->index();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['reservation_id', 'payment_status_id']);
            $table->index(['payment_method_id', 'payment_type_id']);
            $table->index(['created_by', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
