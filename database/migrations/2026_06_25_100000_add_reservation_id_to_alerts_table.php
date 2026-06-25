<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alerts', function (Blueprint $table): void {
            $table->foreignId('reservation_id')
                ->nullable()
                ->after('vehicle_id')
                ->constrained()
                ->nullOnDelete();
        });

        $reservationTypeId = DB::table('alert_types')->where('slug', 'reservation_follow_up')->value('id');

        if ($reservationTypeId) {
            $alerts = DB::table('alerts')
                ->where('alert_type_id', $reservationTypeId)
                ->where('title', 'like', 'New reservation %')
                ->get(['id', 'title']);

            foreach ($alerts as $alert) {
                $reservationNumber = substr($alert->title, strlen('New reservation '));
                $reservationId = DB::table('reservations')
                    ->where('reservation_number', $reservationNumber)
                    ->value('id');

                if ($reservationId) {
                    DB::table('alerts')->where('id', $alert->id)->update([
                        'reservation_id' => $reservationId,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('reservation_id');
        });
    }
};
