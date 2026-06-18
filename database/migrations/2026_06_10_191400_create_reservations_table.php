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
        Schema::create('reservations', function (Blueprint $table): void {
            $table->id();
            $table->string('reservation_number')->unique();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->foreignId('source_id')->constrained('reservation_sources')->restrictOnDelete();
            $table->foreignId('status_id')->constrained('reservation_statuses')->restrictOnDelete();
            $table->foreignId('payment_status_id')->constrained('payment_statuses')->restrictOnDelete();
            $table->foreignId('pickup_location_id')->constrained('locations')->restrictOnDelete();
            $table->foreignId('dropoff_location_id')->constrained('locations')->restrictOnDelete();
            $table->dateTime('start_datetime')->index();
            $table->dateTime('end_datetime')->index();
            $table->unsignedInteger('total_days');
            $table->decimal('price_per_day', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable()->index();
            $table->timestamp('started_at')->nullable()->index();
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamp('cancelled_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vehicle_id', 'start_datetime', 'end_datetime']);
            $table->index(['status_id', 'vehicle_id']);
            $table->index(['customer_id', 'status_id']);
            $table->index(['pickup_location_id', 'dropoff_location_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
