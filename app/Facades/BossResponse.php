<?php

namespace App\Facades;

use App\Helpers\JsonResponseHandler;
use Illuminate\Support\Facades\Facade;

/**
 * @method static JsonResponseHandler withPayload(mixed $payload)
 * @method static JsonResponseHandler withMessage(string $message)
 * @method static JsonResponseHandler withData(mixed $data)
 * @method static JsonResponseHandler withMeta(mixed $metaData)
 * @method static JsonResponseHandler withHttpStatusCode(int $code)
 * @method static JsonResponseHandler withErrorCode(int $code)
 * @method static JsonResponseHandler withStackTrace(array $stacktrace)
 * @method static JsonResponseHandler withHeaders(array $headers)
 * @method static \Illuminate\Http\JsonResponse asSuccess()
 * @method static \Illuminate\Http\JsonResponse asFailed()
 *
 */
class BossResponse extends Facade
{
    /**
     * Get the registered name of the facade.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'boss-response';
    }
}
