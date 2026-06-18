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
        Schema::create('vehicle_brands', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicle_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')->constrained('vehicle_brands')->restrictOnDelete();
            $table->foreignId('category_id')->constrained('vehicle_categories')->restrictOnDelete();
            $table->foreignId('status_id')->constrained('vehicle_statuses')->restrictOnDelete();
            $table->foreignId('transmission_type_id')->constrained('transmission_types')->restrictOnDelete();
            $table->foreignId('fuel_type_id')->constrained('fuel_types')->restrictOnDelete();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('model')->index();
            $table->unsignedSmallInteger('year')->index();
            $table->string('plate_number')->unique();
            $table->unsignedInteger('mileage')->default(0);
            $table->timestamp('current_mileage_updated_at')->nullable();
            $table->unsignedTinyInteger('seats');
            $table->unsignedTinyInteger('doors');
            $table->decimal('daily_price', 10, 2);
            $table->decimal('weekly_price', 10, 2)->nullable();
            $table->decimal('monthly_price', 10, 2)->nullable();
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['brand_id', 'category_id']);
            $table->index(['status_id', 'is_active']);
            $table->index(['transmission_type_id', 'fuel_type_id']);
        });

        Schema::create('vehicle_photos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt_text')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_primary')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vehicle_id', 'is_primary']);
        });

        Schema::create('vehicle_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained('document_types')->restrictOnDelete();
            $table->string('title');
            $table->string('file_path');
            $table->date('expires_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['vehicle_id', 'document_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_documents');
        Schema::dropIfExists('vehicle_photos');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('vehicle_categories');
        Schema::dropIfExists('vehicle_brands');
    }
};
