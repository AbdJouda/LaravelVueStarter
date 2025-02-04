<?php

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Config;

class ApiVersionResolver
{
    /**
     * ResourceMapper constructor.
     *
     * Initializes the version for the ResourceMapper instance based on the request.
     *
     * @throws BindingResolutionException
     */
    public function __construct(protected int|string $version = 1)
    {
        $this->version = $this->getRequestVersion();
    }


    /**
     * Get Version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Get the version for the API request.
     *
     * @return string
     * @throws BindingResolutionException
     */
    protected function getRequestVersion(): string
    {
        $requestedVersion = Container::getInstance()->make('request')->header('api-version', 1);
        return in_array($requestedVersion, Config::get('settings.api.versions'))
            ? $requestedVersion
            : Config::get('settings.api.current_version');
    }
}
