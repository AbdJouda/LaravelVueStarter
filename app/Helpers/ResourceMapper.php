<?php

namespace App\Helpers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ResourceMapper
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
        $this->version = app(ApiVersionResolver::class)->getVersion();
    }

    /**
     * Transform the data using the appropriate resource.
     *
     * @param mixed $data
     * @return mixed
     */
    public function transform(mixed $data): mixed
    {

        if (!is_object($data) || $data instanceof JsonResource) {
            return $data;
        }

        $method = $data instanceof Collection ? 'collection' : 'make';

        $resourceClass = $this->getResourceClass(
            $data instanceof Collection
                ? $data->first()
                : $data);

        return $resourceClass && class_exists($resourceClass)
            ? $resourceClass::$method($data)
            : $data;
    }


    /**
     * Get the resource class for the given data type.
     *
     * @param mixed $data
     * @return string|null
     */
    public function getResourceClass(mixed $data): ?string
    {
        if (!is_object($data)) {
            return $data;
        }

        $class = get_class($data);

        return config('resources.' . $class . '.v' . $this->version) ?? null;


    }

    /**
     * Get the class name for the given data.
     *
     * @param mixed $data
     * @return string
     */
    protected function getClass(mixed $data): string
    {
        return is_object($data) ? get_class($data) : (string)$data;
    }


}
