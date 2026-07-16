<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('vehicles', 'homepage_rank')) {
            Schema::table('vehicles', function (Blueprint $table): void {
                $table->unsignedTinyInteger('homepage_rank')->nullable()->after('is_featured');
                $table->unique('homepage_rank');
            });
        }

        if (! Schema::hasTable('vehicle_availability_holds')) {
            Schema::create('vehicle_availability_holds', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
                $table->dateTime('starts_at');
                $table->dateTime('ends_at');
                $table->string('customer_name');
                $table->string('phone')->nullable();
                $table->text('note')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['vehicle_id', 'starts_at', 'ends_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_availability_holds');

        if (Schema::hasColumn('vehicles', 'homepage_rank')) {
            Schema::table('vehicles', function (Blueprint $table): void {
                $table->dropUnique(['homepage_rank']);
                $table->dropColumn('homepage_rank');
            });
        }
    }
};
