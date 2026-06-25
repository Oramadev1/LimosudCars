<?php

use App\Models\Customer;
use App\Support\IdentityDocument;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->string('passport_or_cin_normalized')->nullable()->after('passport_or_cin')->index();
        });

        Customer::withTrashed()->each(function (Customer $customer): void {
            $customer->passport_or_cin_normalized = IdentityDocument::normalize($customer->passport_or_cin);
            $customer->saveQuietly();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn('passport_or_cin_normalized');
        });
    }
};
