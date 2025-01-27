<?php

use App\Models\AppSetting;
use Illuminate\Support\Facades\Storage;

if (!function_exists('setting')) {

    function appSetting(string $key, $default = null, bool $isUrl = false)
    {
        $value = AppSetting::get($key, $default);

        return $isUrl
            ? Storage::disk('public')->url($value)
            : $value;
    }
}



if (!function_exists('stringContainsAny')) {

    /**
     * Checks if the given string contains any of the specified words.
     *
     * @param string $string
     * @param array $words
     *
     * @return bool
     */
    function stringContainsAny(string $string, array $words): bool
    {
        foreach ($words as $word) {
            if (str_contains($string, $word)) {
                return true;
            }
        }
        return false;
    }

}

if (!function_exists('capitalizeString')) {

    /**
     * Capitalizes a string and formats it to title case.
     *
     * @param string $str
     * @return string
     */
    function capitalizeString(string $str): string
    {
        $str = str_replace('_', ' ', $str);

        return ucwords(strtolower($str));
    }

}
