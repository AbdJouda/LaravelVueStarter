<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class AppSetting extends BaseCachableModel
{
    use HasFactory, HasUuids;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Retrieve Setting Value by given key
     *
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public static function get($key, $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Cast images values
     *
     * @return Attribute
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->isImage($value)
                ? Storage::disk('public')->url($value)
                : $value,
        );
    }

    /**
     * Check if file name is an image
     *
     * @param string $filename
     * @return bool
     */
    private function isImage(string $filename): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($ext, $imageExtensions);
    }

}
