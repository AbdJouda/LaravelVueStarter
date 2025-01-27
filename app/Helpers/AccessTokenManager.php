<?php

namespace App\Helpers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AccessTokenManager implements Arrayable, Jsonable, JsonSerializable
{

    /**
     * Create a new AccessTokenManager instance.
     *
     * @param mixed $user
     * @param bool $revokePreviousTokens
     * @param array $abilities
     */
    public function __construct(protected mixed $user, protected bool $revokePreviousTokens = false, protected array $abilities = ['*'])
    {

    }

    /**
     * Build Token object payload
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[ArrayShape([
        'token' => "string",
        'access_type' => "string",
        'abilities' => "array|string[]",
        'expires_in' => "\Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed"])
    ]
    private function buildPayload(): array
    {
        if ($this->revokePreviousTokens)
            $this->user->tokens()->delete();

        return [
            'token' => $this->generateToken(),
            'access_type' => 'Bearer',
            'abilities' => $this->abilities,
            'expires_in' => config('sanctum.expiration')
        ];

    }

    /**
     * Generate user access token
     *
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function generateToken(): string
    {
        return $this->user->createToken($this->user->getTable(), $this->abilities)->plainTextToken;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function toArray(): array
    {
        return $this->buildPayload();
    }

    /**
     * Convert the array to its JSON representation.
     *
     * @param int $options
     * @return string
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }


    /**
     * Convert the array into something JSON serializable.
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
