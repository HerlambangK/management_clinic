<?php

namespace App\Services;

use App\Models\OperatingHour;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AttendanceService
{
    public const MIN_TOKEN_LENGTH = 8;

    public const MAX_TOKEN_LENGTH = 12;

    public const TOKEN_SEPARATOR = '|';

    public const DEFAULT_RADIUS_METERS = 100;

    public function buildQrPayload(string $token, ?Carbon $timestamp = null): string
    {
        $timestamp ??= now();

        return $token.self::TOKEN_SEPARATOR.$timestamp->toIso8601String();
    }

    public function extractToken(?string $payload): ?string
    {
        if (! $payload) {
            return null;
        }

        $value = trim($payload);

        if ($value === '') {
            return null;
        }

        if (str_starts_with($value, 'ATTEND:')) {
            $value = substr($value, strlen('ATTEND:'));
        }

        $fromUrl = $this->extractTokenFromUrl($value);
        if ($fromUrl) {
            $value = $fromUrl;
        }

        if (str_contains($value, self::TOKEN_SEPARATOR)) {
            $value = explode(self::TOKEN_SEPARATOR, $value, 2)[0];
        }

        $value = trim($value);

        return $value !== '' ? $value : null;
    }

    public function generateToken(?int $length = null): string
    {
        $length ??= random_int(self::MIN_TOKEN_LENGTH, self::MAX_TOKEN_LENGTH);

        return Str::upper(Str::random($length));
    }

    public function generateUniqueToken(): string
    {
        for ($i = 0; $i < 10; $i++) {
            $token = $this->generateToken();

            if (! Store::query()->where('attendance_token', $token)->exists()) {
                return $token;
            }
        }

        return $this->generateToken();
    }

    public function isWithinOperatingHours(Store $store, ?Carbon $now = null): bool
    {
        $now ??= now();

        $hours = OperatingHour::query()
            ->withoutGlobalScopes()
            ->where('store_id', $store->id)
            ->where('day_of_week', $now->dayOfWeek)
            ->first();

        if (! $hours || $hours->is_closed || ! $hours->open_time || ! $hours->close_time) {
            return false;
        }

        $openAt = Carbon::parse($now->toDateString().' '.$hours->open_time);
        $closeAt = Carbon::parse($now->toDateString().' '.$hours->close_time);

        if ($closeAt->lessThanOrEqualTo($openAt)) {
            $closeAt->addDay();
        }

        return $now->betweenIncluded($openAt, $closeAt);
    }

    public function getActiveToken(Store $store, bool $autoGenerate = true, ?Carbon $now = null): ?string
    {
        $now ??= now();

        if (! $this->isWithinOperatingHours($store, $now)) {
            return null;
        }

        $token = $store->attendance_token;
        $generatedAt = $store->attendance_token_generated_at;

        if ($token && $generatedAt && $generatedAt->isSameDay($now)) {
            return $token;
        }

        if (! $autoGenerate) {
            return null;
        }

        return $this->regenerateToken($store, $now);
    }

    public function regenerateToken(Store $store, ?Carbon $now = null): string
    {
        $now ??= now();
        $token = $this->generateUniqueToken();

        $store->forceFill([
            'attendance_token' => $token,
            'attendance_token_generated_at' => $now,
        ])->save();

        return $token;
    }

    public function distanceInMeters(float $latFrom, float $lngFrom, float $latTo, float $lngTo): float
    {
        $earthRadius = 6371000;
        $latFromRad = deg2rad($latFrom);
        $lngFromRad = deg2rad($lngFrom);
        $latToRad = deg2rad($latTo);
        $lngToRad = deg2rad($lngTo);

        $latDelta = $latToRad - $latFromRad;
        $lngDelta = $lngToRad - $lngFromRad;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2)
            + cos($latFromRad) * cos($latToRad) * pow(sin($lngDelta / 2), 2)
        ));

        return $angle * $earthRadius;
    }

    public function isWithinRadius(Store $store, float $latitude, float $longitude, int $radiusMeters = self::DEFAULT_RADIUS_METERS): bool
    {
        if ($store->latitude === null || $store->longitude === null) {
            return false;
        }

        $distance = $this->distanceInMeters(
            (float) $store->latitude,
            (float) $store->longitude,
            $latitude,
            $longitude
        );

        return $distance <= $radiusMeters;
    }

    private function extractTokenFromUrl(string $value): ?string
    {
        if (! str_starts_with($value, 'http')) {
            return null;
        }

        $parts = parse_url($value);
        if (! $parts || empty($parts['query'])) {
            return null;
        }

        parse_str($parts['query'], $query);

        if (! is_array($query)) {
            return null;
        }

        $token = $query['token'] ?? null;

        return is_string($token) ? $token : null;
    }
}
