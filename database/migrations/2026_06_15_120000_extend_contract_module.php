<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->string('address')->nullable()->after('email');
            $table->string('foreign_address')->nullable()->after('address');
            $table->date('driving_license_issued_at')->nullable()->after('driving_license_number');
            $table->date('driving_license_expires_at')->nullable()->after('driving_license_issued_at');
            $table->string('driving_license_country')->nullable()->after('driving_license_expires_at');
            $table->date('passport_or_cin_issued_at')->nullable()->after('passport_or_cin_normalized');
        });

        Schema::table('vehicles', function (Blueprint $table): void {
            $table->string('vin')->nullable()->after('plate_number');
            $table->string('color')->nullable()->after('vin');
            $table->string('fuel_level')->nullable()->after('mileage');
        });

        Schema::table('contracts', function (Blueprint $table): void {
            $table->string('contract_series', 8)->default('A')->after('contract_number');
            $table->json('details')->nullable()->after('signed_pdf_path');
        });

        Schema::create('contract_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('company_name');
            $table->string('company_legal_name');
            $table->string('company_tagline')->nullable();
            $table->string('company_address');
            $table->string('company_email');
            $table->string('company_phone');
            $table->string('company_phone_alt')->nullable();
            $table->string('company_city');
            $table->string('stamp_address')->nullable();
            $table->string('stamp_phones')->nullable();
            $table->unsignedInteger('insurance_deductible')->default(5000);
            $table->text('terms_fr')->nullable();
            $table->text('terms_ar')->nullable();
            $table->timestamps();
        });

        $company = config('limosud.company');

        DB::table('contract_settings')->insert([
            'company_name' => $company['name'],
            'company_legal_name' => $company['legal_name'],
            'company_tagline' => $company['tagline'],
            'company_address' => $company['address'],
            'company_email' => $company['email'],
            'company_phone' => $company['phone'],
            'company_phone_alt' => $company['phone_alt'],
            'company_city' => $company['city'],
            'stamp_address' => $company['stamp_address'],
            'stamp_phones' => $company['stamp_phones'],
            'insurance_deductible' => $company['insurance_deductible'],
            'terms_fr' => config('limosud.contract_legal_fr'),
            'terms_ar' => config('limosud.contract_legal_ar'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_settings');

        Schema::table('contracts', function (Blueprint $table): void {
            $table->dropColumn(['contract_series', 'details']);
        });

        Schema::table('vehicles', function (Blueprint $table): void {
            $table->dropColumn(['vin', 'color', 'fuel_level']);
        });

        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn([
                'address',
                'foreign_address',
                'driving_license_issued_at',
                'driving_license_expires_at',
                'driving_license_country',
                'passport_or_cin_issued_at',
            ]);
        });
    }
};
