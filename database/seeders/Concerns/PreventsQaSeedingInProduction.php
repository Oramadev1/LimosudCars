<?php

namespace Database\Seeders\Concerns;

use RuntimeException;

trait PreventsQaSeedingInProduction
{
    /**
     * QA seeders must only run in local/testing environments.
     */
    protected function preventQaSeedingInProduction(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException(
                'QA seeders are disabled in production. Use DatabaseSeeder for normal bootstrap data only.'
            );
        }
    }
}
