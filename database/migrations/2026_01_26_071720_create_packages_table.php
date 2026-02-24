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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('total_sessions')->comment('Number of treatment sessions included');
            $table->decimal('original_price', 12, 2)->comment('Total if bought individually');
            $table->decimal('package_price', 12, 2)->comment('Discounted package price');
            $table->integer('validity_days')->default(365)->comment('Days valid after purchase');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
