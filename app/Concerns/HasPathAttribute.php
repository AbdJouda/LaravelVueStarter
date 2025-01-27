<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Support\Facades\Storage;

trait HasPathAttribute
{

    /**
     * Check if the attribute is a URL attribute.
     *
     * @param string $key
     * @return bool
     */
    private function isUrlAttribute(string $key): bool
    {
        return str_ends_with($key, '_url');
    }

    /**
     * Check if the attribute is a path attribute.
     *
     * @param string $key
     * @return bool
     */
    private function isPathAttribute(string $key): bool
    {
        return str_ends_with($key, '_path');
    }

    /**
     * Retrieve the value of an attribute by its key.
     *
     * @param string $key The key of the attribute.
     * @return mixed The value of the attribute, or the generated URL if the attribute is a URL attribute.
     * @throws RelationNotFoundException
     */
    public function __get($key)
    {
        if ($this->isUrlAttribute($key)) {
            return $this->generateUrlAttribute($key);
        }

        return parent::__get($key);
    }

    /**
     * Generate the URL attribute based on the original path attribute.
     *
     * @param string $key
     * @return string|null
     */
    private function generateUrlAttribute(string $key): ?string
    {
        $originalPathAttribute = $this->generateOriginalPathAttributeName($key);

        $value = data_get($this->attributes, $originalPathAttribute);

        if (is_null($value) && in_array($originalPathAttribute, ['logo_path', 'profile_photo_path'])) {

            return $this->generateDefaultImagePath();

        } else {

            return $value ? Storage::disk('public')->url($value) : null;

        }

    }

    /**
     * Generate the original path attribute name based on the URL attribute name.
     *
     * @param string $key
     * @return string
     */
    private function generateOriginalPathAttributeName(string $key): string
    {
        return substr($key, 0, -4) . '_path';
    }

    /**
     * Get the appendable values that are arrayable.
     *
     * @return array
     */
    protected function getArrayableAppends(): array
    {
        foreach ($this->attributes as $key => $value) {
            if ($this->isPathAttribute($key)) {
                $this->appends[] = substr($key, 0, -5) . '_url';
            }
        }

        return parent::getArrayableAppends();
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (str_starts_with($method, 'get') && str_ends_with($method, 'UrlAttribute')) {
            return $this->generateUrlAttribute($this->convertAccessorMethodToOriginalAttributeName($method));
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Convert the accessor method name to the original attribute name.
     *
     * @param string $methodName
     * @return string
     */
    private function convertAccessorMethodToOriginalAttributeName(string $methodName): string
    {
        // Remove 'get' from the beginning and 'UrlAttribute' from the end of the method name
        $trimmedName = substr($methodName, 3, -12);

        // Convert camelCase to snake_case
        $snakeCaseName = strtolower(preg_replace('/[A-Z]/', '_$0', $trimmedName));

        // Append 'path' to the snake_case name
        $pathAttribute = $snakeCaseName . 'path';

        return ltrim($pathAttribute, '_');

    }

    /**
     * Generate Default Image Path
     *
     * @return string
     */
    public function generateDefaultImagePath(): string
    {
        $value = env('APP_NAME');

        if (!is_null($name = $this->getAttribute('name'))) {
            $value = $this->getInitials($name, ' ');
        }
        if (!is_null($fullName = $this->getAttribute('full_name'))) {
            $value = $this->getInitials($fullName, ' ');
        }

        if (!is_null($username = $this->getAttribute('username'))) {
            $value = $this->getInitials($username, '.');
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($value) . '&color=099faf&background=FFFFFF&format=png';

    }

    /**
     * Get String Initials
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    private function getInitials(string $string, string $separator = ' '): string
    {
        return trim(collect(explode($separator, $string))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));
    }
}

