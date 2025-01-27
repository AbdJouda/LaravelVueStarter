<?php

namespace App\Exceptions;

use App\Helpers\RequestsThrottle;
use Exception;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response as Status;

class HumanReadableException extends Exception
{
    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * @link http://php.net/manual/en/exception.construct.php
     * @param string $message [optional] The Exception message to throw.
     * @param int $statusCode [optional] The HTTP Status code.
     * @param int $code [optional] The Exception code.
     * @since 5.1.0
     */
    #[Pure] public function __construct(string $message, protected int $statusCode = Status::HTTP_FORBIDDEN, int $code = 0)
    {

        parent::__construct($message, $code);
    }

    /**
     * Throw throttle exception.
     *
     * @param null $request
     * @param RequestsThrottle $limit
     * @return HumanReadableException
     */
    public static function tooManyRequests($request, RequestsThrottle $limit): HumanReadableException
    {
        return new static(
            __('auth.throttle', ['seconds' => $limit->availableIn($request)]),
            Status::HTTP_TOO_MANY_REQUESTS
        );
    }

    /**
     * Get Http Status code
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

}
