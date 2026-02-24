<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->timestamp('attendance_token_generated_at')->nullable()->after('attendance_token');
            $table->decimal('latitude', 10, 7)->nullable()->after('attendance_token_generated_at');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });

        $now = now();
        $tokens = [];
        $storeIds = DB::table('stores')->pluck('id');

        foreach ($storeIds as $storeId) {
            $token = $this->generateUniqueToken($tokens);
            $tokens[] = $token;

            DB::table('stores')->where('id', $storeId)->update([
                'attendance_token' => $token,
                'attendance_token_generated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['attendance_token_generated_at', 'latitude', 'longitude']);
        });
    }

    /**
     * @param  array<string>  $takenTokens
     */
    private function generateUniqueToken(array $takenTokens): string
    {
        for ($i = 0; $i < 10; $i++) {
            $length = random_int(8, 12);
            $token = Str::upper(Str::random($length));

            if (in_array($token, $takenTokens, true)) {
                continue;
            }

            if (! DB::table('stores')->where('attendance_token', $token)->exists()) {
                return $token;
            }
        }

        return Str::upper(Str::random(10));
    }
};
