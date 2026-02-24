<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new JSON columns first
        Schema::table('treatment_records', function (Blueprint $table) {
            $table->json('before_photos')->nullable()->after('recommendations');
            $table->json('after_photos')->nullable()->after('before_photos');
        });

        // Migrate existing data from old columns to new columns
        DB::table('treatment_records')->whereNotNull('before_photo')->orderBy('id')->each(function ($record) {
            DB::table('treatment_records')
                ->where('id', $record->id)
                ->update(['before_photos' => json_encode([$record->before_photo])]);
        });

        DB::table('treatment_records')->whereNotNull('after_photo')->orderBy('id')->each(function ($record) {
            DB::table('treatment_records')
                ->where('id', $record->id)
                ->update(['after_photos' => json_encode([$record->after_photo])]);
        });

        // Drop old columns
        Schema::table('treatment_records', function (Blueprint $table) {
            $table->dropColumn(['before_photo', 'after_photo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back old columns
        Schema::table('treatment_records', function (Blueprint $table) {
            $table->string('before_photo')->nullable();
            $table->string('after_photo')->nullable();
        });

        // Copy first photo from JSON array back to single column
        DB::table('treatment_records')->whereNotNull('before_photos')->orderBy('id')->each(function ($record) {
            $photos = json_decode($record->before_photos, true);
            if (! empty($photos)) {
                DB::table('treatment_records')
                    ->where('id', $record->id)
                    ->update(['before_photo' => $photos[0]]);
            }
        });

        DB::table('treatment_records')->whereNotNull('after_photos')->orderBy('id')->each(function ($record) {
            $photos = json_decode($record->after_photos, true);
            if (! empty($photos)) {
                DB::table('treatment_records')
                    ->where('id', $record->id)
                    ->update(['after_photo' => $photos[0]]);
            }
        });

        // Drop new columns
        Schema::table('treatment_records', function (Blueprint $table) {
            $table->dropColumn(['before_photos', 'after_photos']);
        });
    }
};
