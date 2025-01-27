<?php

namespace App\Concerns;

use App\Exceptions\HumanReadableException;
use App\Facades\BossResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Exceptions\BackedEnumCaseNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use JetBrains\PhpStorm\Pure;
use stdClass;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpFoundation\Response as Status;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use function __;
use function config;

trait JsonExceptionHandler
{

    /**
     * A list of the exception types that can be reported to users.
     *
     * @var array
     */
    protected array $humanReadableExceptions = [
        HumanReadableException::class,
        ThrottleRequestsException::class,
        ModelNotFoundException::class
    ];

    /**
     * Override Exception messages
     *
     * @var array
     */
    protected array $customMessages = [
        AccessDeniedHttpException::class => 'Permission to access was denied.',
        AuthorizationException::class => 'Permission to access was denied.',
        ThrottleRequestsException::class => 'Too many attempts. Try again later.',
    ];

    /**
     * {@inheritdoc}
     *
     * @return JsonResponse
     */
    protected function prepareJsonResponse($request, Throwable $e): JsonResponse
    {

        return BossResponse::withData(new stdClass())
            ->withMeta(new stdClass())
            ->withMessage($this->handleExceptionMessage($e))
            ->withStackTrace($this->convertExceptionToArray($e))
            ->withHttpStatusCode($this->getExceptionStatusCode($e))
            ->withErrorCode($this->getExceptionErrorCode($e))
            ->asFailed();
    }

    /**
     * {@inheritdoc}
     *
     * @param Request $request
     * @param ValidationException $exception
     * @return  JsonResponse
     * @throws Exception
     */
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return BossResponse::withMessage($exception->getMessage())
            ->withData($exception->errors())
            ->withHttpStatusCode($exception->status)
            ->asFailed();

    }


    /**
     * Convert an authentication exception into a response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return  JsonResponse
     * @throws Exception
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse
    {
        return BossResponse::withMessage($exception->getMessage())
            ->withHttpStatusCode(Status::HTTP_UNAUTHORIZED)
            ->asFailed();

    }

    /**
     * Prepare error message based on current environment
     *
     * @param $e
     * @return string
     */
    private function handleExceptionMessage($e): string
    {
        $class = get_class($e);

        if (in_array($class, array_keys($this->customMessages)))
            return $this->customMessages[$class];

        return !in_array($class, $this->humanReadableExceptions) && !config('app.debug')
            ? __('We are facing some issues proceeding with your request, please try again later.')
            : $e->getMessage();
    }

    /**
     * Get HTTP status code for response
     *
     * @param Throwable $e
     * @return int
     */
    #[Pure] public function getExceptionStatusCode(Throwable $e): int
    {
        if (method_exists($e, 'getStatusCode'))
            return $e->getStatusCode();

        if (method_exists($e, 'getHttpStatusCode'))
            return $e->getHttpStatusCode();

        return Status::HTTP_INTERNAL_SERVER_ERROR;


    }

    /**
     * Get Custom error code for response
     *
     * @param Throwable $e
     * @return int
     */
    public function getExceptionErrorCode(Throwable $e): int
    {
        return method_exists($e, 'getCustomErrorCode')
            ? $e->getCode()
            : 0;

    }

    /**
     * Determine if the exception is in the "do not report" list.
     *
     * @param Throwable $e
     * @return bool
     */
    protected function shouldntReport(Throwable $e)
    {
        $dontReport = array_merge($this->dontReport, $this->internalDontReport, $this->humanReadableExceptions);

        return !is_null(Arr::first($dontReport, fn($type) => $e instanceof $type));
    }

    /**
     * Prepare exception for rendering.
     *
     * @param Throwable $e
     * @return HttpException|NotFoundHttpException|Throwable|AccessDeniedHttpException
     */
    public function prepareException(Throwable $e): HttpException|NotFoundHttpException|Throwable|AccessDeniedHttpException
    {
        return match (true) {
            $e instanceof BackedEnumCaseNotFoundException => new NotFoundHttpException($e->getMessage(), $e),
            $e instanceof ModelNotFoundException, $e instanceof RecordsNotFoundException, $e instanceof MethodNotAllowedHttpException => new HumanReadableException(
                __('Sorry, the requested resource could not be found. Please check the URL or try again later.'),
                Status::HTTP_NOT_FOUND),
            $e instanceof AuthorizationException && $e->hasStatus() => new HttpException(
                $e->status(), $e->response()?->message() ?: (Response::$statusTexts[$e->status()] ?? 'Whoops, looks like something went wrong.'), $e
            ),
            $e instanceof AuthorizationException && !$e->hasStatus() => new AccessDeniedHttpException($e->getMessage(), $e),
            $e instanceof TokenMismatchException => new HttpException(419, $e->getMessage(), $e),
            $e instanceof SuspiciousOperationException => new NotFoundHttpException('Bad hostname provided.', $e),
            default => $e,
        };
    }
}
