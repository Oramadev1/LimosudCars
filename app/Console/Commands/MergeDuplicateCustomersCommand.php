<?php

namespace App\Console\Commands;

use App\Services\CustomerService;
use Illuminate\Console\Command;

class MergeDuplicateCustomersCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'customers:merge-duplicates';

    /**
     * @var string
     */
    protected $description = 'Merge duplicate customers that share the same passport/CIN or phone number';

    public function handle(CustomerService $customerService): int
    {
        $removed = $customerService->mergeDuplicates();

        $this->info("Merged {$removed} duplicate customer record(s).");

        return self::SUCCESS;
    }
}
