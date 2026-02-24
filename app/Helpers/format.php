<?php

use Carbon\Carbon;

if (! function_exists('format_currency')) {
    /**
     * Format number as Indonesian Rupiah currency.
     *
     * @param  bool  $withPrefix  Include "Rp " prefix
     */
    function format_currency(float|int|string|null $amount, bool $withPrefix = true): string
    {
        $amount = (float) ($amount ?? 0);
        $formatted = number_format($amount, 0, ',', '.');

        return $withPrefix ? 'Rp '.$formatted : $formatted;
    }
}

if (! function_exists('format_number')) {
    /**
     * Format number with Indonesian locale (dot as thousand separator).
     *
     * @param  int  $decimals  Number of decimal places
     */
    function format_number(float|int|string|null $number, int $decimals = 0): string
    {
        $number = (float) ($number ?? 0);

        return number_format($number, $decimals, ',', '.');
    }
}

if (! function_exists('format_date')) {
    /**
     * Format date in Indonesian format (d/m/Y).
     *
     * @param  string  $format  Default format
     */
    function format_date(Carbon|string|null $date, string $format = 'd/m/Y'): string
    {
        if (! $date) {
            return '-';
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->format($format);
    }
}

if (! function_exists('format_datetime')) {
    /**
     * Format datetime in Indonesian format (d/m/Y H:i).
     *
     * @param  string  $format  Default format
     */
    function format_datetime(Carbon|string|null $datetime, string $format = 'd/m/Y H:i'): string
    {
        if (! $datetime) {
            return '-';
        }

        if (is_string($datetime)) {
            $datetime = Carbon::parse($datetime);
        }

        return $datetime->format($format);
    }
}

if (! function_exists('format_time')) {
    /**
     * Format time in H:i format.
     *
     * @param  string  $format  Default format
     */
    function format_time(Carbon|string|null $time, string $format = 'H:i'): string
    {
        if (! $time) {
            return '-';
        }

        if (is_string($time)) {
            $time = Carbon::parse($time);
        }

        return $time->format($format);
    }
}
