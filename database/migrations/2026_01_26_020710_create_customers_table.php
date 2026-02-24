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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20)->unique();
            $table->string('email')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->enum('skin_type', ['normal', 'oily', 'dry', 'combination', 'sensitive'])->nullable();
            $table->json('skin_concerns')->nullable();
            $table->text('allergies')->nullable();
            $table->text('notes')->nullable();
            $table->integer('total_visits')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0);
            $table->date('last_visit')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
