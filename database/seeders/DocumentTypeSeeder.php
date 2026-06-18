<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Database\Seeders\Concerns\SeedsLookupTables;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    use SeedsLookupTables;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLookupValues(DocumentType::class, [
            'passport' => 'Passport',
            'cin' => 'CIN',
            'driving_license' => 'Driving License',
            'vehicle_registration' => 'Vehicle Registration',
            'insurance' => 'Insurance',
            'technical_inspection' => 'Technical Inspection',
            'contract' => 'Contract',
            'invoice' => 'Invoice',
            'other' => 'Other',
        ]);
    }
}
