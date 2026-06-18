<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<int, string>
     */
    private array $tables = [
        'vehicle_statuses',
        'transmission_types',
        'fuel_types',
        'reservation_statuses',
        'payment_statuses',
        'payment_methods',
        'payment_types',
        'reservation_sources',
        'location_types',
        'maintenance_types',
        'expense_categories',
        'alert_types',
        'alert_statuses',
        'document_types',
        'contract_statuses',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->string('name')->index();
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (array_reverse($this->tables) as $tableName) {
            Schema::dropIfExists($tableName);
        }
    }
};
