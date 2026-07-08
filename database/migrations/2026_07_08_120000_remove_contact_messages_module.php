<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('alerts', 'contact_message_id')) {
            Schema::table('alerts', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('contact_message_id');
            });
        }

        Schema::dropIfExists('contact_messages');
    }

    public function down(): void
    {
        Schema::create('contact_messages', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::table('alerts', function (Blueprint $table): void {
            $table->foreignId('contact_message_id')
                ->nullable()
                ->after('reservation_id')
                ->constrained()
                ->nullOnDelete();
        });
    }
};
