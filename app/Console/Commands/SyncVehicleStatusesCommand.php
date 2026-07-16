<?php

namespace App\Console\Commands;

use App\Services\VehicleStatusSyncService;
use Illuminate\Console\Command;

class SyncVehicleStatusesCommand extends Command
{
    protected $signature = 'vehicles:sync-statuses';

    protected $description = 'Sync vehicle statuses from calendar holds and active reservations';

    public function handle(VehicleStatusSyncService $syncService): int
    {
        $result = $syncService->sync();

        $this->info("Updated {$result['updated']} vehicle(s); skipped {$result['skipped']}.");

        return self::SUCCESS;
    }
}
