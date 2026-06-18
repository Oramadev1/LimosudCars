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
        Schema::create('vehicle_maintenances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->foreignId('maintenance_type_id')->constrained('maintenance_types')->restrictOnDelete();
            $table->date('maintenance_date')->index();
            $table->date('next_maintenance_date')->nullable()->index();
            $table->unsignedInteger('mileage')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('garage_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vehicle_id', 'maintenance_type_id']);
        });

        Schema::create('expenses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('expense_category_id')->constrained('expense_categories')->restrictOnDelete();
            $table->decimal('amount', 10, 2);
            $table->date('expense_date')->index();
            $table->text('description')->nullable();
            $table->string('invoice_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vehicle_id', 'expense_category_id']);
            $table->index(['created_by', 'expense_date']);
        });

        Schema::create('alerts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('alert_type_id')->constrained('alert_types')->restrictOnDelete();
            $table->foreignId('alert_status_id')->constrained('alert_statuses')->restrictOnDelete();
            $table->string('title');
            $table->text('message')->nullable();
            $table->date('due_date')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vehicle_id', 'alert_type_id', 'alert_status_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('vehicle_maintenances');
    }
};
