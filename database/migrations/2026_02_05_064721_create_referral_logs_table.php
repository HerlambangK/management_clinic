<?php

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
        Schema::create('referral_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('referee_id')->constrained('customers')->cascadeOnDelete();
            $table->unsignedInteger('referrer_points')->default(0);
            $table->unsignedInteger('referee_points')->default(0);
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'rewarded', 'cancelled'])->default('pending');
            $table->timestamp('rewarded_at')->nullable();
            $table->timestamps();

            $table->unique(['referrer_id', 'referee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_logs');
    }
};
