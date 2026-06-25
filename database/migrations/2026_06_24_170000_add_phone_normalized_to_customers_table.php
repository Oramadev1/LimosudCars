<?php

use App\Models\Customer;
use App\Support\PhoneNumber;
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
            $table->string('phone_normalized')->nullable()->after('phone')->index();
        });

        Customer::withTrashed()->each(function (Customer $customer): void {
            $customer->phone_normalized = PhoneNumber::normalize($customer->phone);
            $customer->saveQuietly();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn('phone_normalized');
        });
    }
};
