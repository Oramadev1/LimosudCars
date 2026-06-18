<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\PreventsQaSeedingInProduction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Local QA-only seeder. Never registered in DatabaseSeeder.
 *
 * Run manually:
 *   php artisan db:seed --class=QaDatabaseSeeder
 */
class QaDatabaseSeeder extends Seeder
{
    use PreventsQaSeedingInProduction;
    use WithoutModelEvents;

    /**
     * Seed base lookups plus QA API test data.
     */
    public function run(): void
    {
        $this->preventQaSeedingInProduction();

        $this->call([
            DatabaseSeeder::class,
            QaApiSeeder::class,
        ]);
    }
}
