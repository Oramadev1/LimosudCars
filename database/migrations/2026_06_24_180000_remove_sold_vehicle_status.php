<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $soldStatusId = DB::table('vehicle_statuses')->where('slug', 'sold')->value('id');

        if ($soldStatusId === null) {
            return;
        }

        $outOfServiceStatusId = DB::table('vehicle_statuses')
            ->where('slug', 'out_of_service')
            ->value('id');

        if ($outOfServiceStatusId !== null) {
            DB::table('vehicles')
                ->where('status_id', $soldStatusId)
                ->update([
                    'status_id' => $outOfServiceStatusId,
                    'updated_at' => now(),
                ]);
        }

        DB::table('vehicle_statuses')->where('id', $soldStatusId)->delete();
    }

    public function down(): void
    {
        $now = now();

        DB::table('vehicle_statuses')->updateOrInsert(
            ['slug' => 'sold'],
            ['name' => 'Sold', 'created_at' => $now, 'updated_at' => $now]
        );
    }
};
