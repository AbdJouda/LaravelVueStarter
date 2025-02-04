<?php

namespace App\Helpers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;
use stdClass;
use Symfony\Component\HttpFoundation\Response as Status;


class JsonResponseHandler
{
    /**
     * Response Payload
     *
     * @var array
     */
    protected array $payload = [];

    /**
     * JsonResponseHandler constructor.
     *
     * @param Request $request
     * @param JsonResponse $response
     * @param ResourceMapper $resourceMapper
     * @param string $message
     * @param int $code
     * @param int $httpStatusCode
     * @param array $stackTrace
     */
    public function __construct(
        protected Request        $request,
        protected JsonResponse   $response,
        protected ResourceMapper $resourceMapper,
        protected string         $message = '',
        protected int            $code = 0,
        protected int            $httpStatusCode = Status::HTTP_OK,
        protected array          $stackTrace = [],
    )
    {
        if ($callback = $this->request->query('callback')) {
            $this->response->withCallback($callback);
        }

        $this->payload = [
            'message' => '',
            'data' => new stdClass(),
            'meta' => [
                'version' => app(ApiVersionResolver::class)->getVersion(),
                'http_status' => Status::HTTP_OK,
            ],
        ];
    }

    /**
     * Set response payload ( override data & meta )
     *
     * @param mixed $payload
     * @return self
     */
    public function withPayload(mixed $payload): self
    {

        $this->payload = $payload;

        return $this;
    }

    /**
     * Set response payload data
     *
     * @param mixed $data
     * @return self
     */
    public function withData(mixed $data): self
    {

        if ($data instanceof LengthAwarePaginator) {

            $this->payload['data'] = $this->resourceMapper->transform(collect($data->items()));
            $this->withMeta(['pagination' => $this->resourceMapper->transform($data)]);
        } else {
            $this->payload['data'] = $this->resourceMapper->transform($data);
        }

        return $this;
    }

    /**
     * Set response payload meta
     *
     * @param mixed $meta
     * @return self
     */
    public function withMeta(mixed $meta): self
    {
        $meta = $meta instanceof Arrayable
            ? $meta->toArray()
            : (array)$meta;

        $this->payload['meta'] = array_merge($this->payload['meta'], $meta);

        return $this;
    }

    /**
     * Set response message
     *
     * @param string $message
     * @return self
     */
    public function withMessage(string $message): self
    {
        $this->payload['message'] = $message;

        return $this;
    }

    /**
     * Add custom response header
     *
     * @param array $headers
     * @return self
     */
    public function withHeaders(array $headers): self
    {
        foreach ($headers as $key => $value)
            $this->response->header($key, $value);

        return $this;
    }


    /**
     * Set http status code
     *
     * @param int $code
     * @return self
     */
    public function withHttpStatusCode(int $code): self
    {
        $this->httpStatusCode = $code;
        $this->withMeta(['http_status' => $code]);
        try {
            $this->response->setStatusCode($code);
        } catch (InvalidArgumentException $exception) {
            $this->httpStatusCode = Status::HTTP_INTERNAL_SERVER_ERROR;
            $this->withMeta(['http_status' => Status::HTTP_INTERNAL_SERVER_ERROR]);
            $this->response->setStatusCode(Status::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this;
    }


    /**
     * Set custom error code
     *
     * @param int $code
     * @return self
     */
    public function withErrorCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set Exception stacktrace
     *
     * @param array $stacktrace
     * @return self
     */
    public function withStackTrace(array $stacktrace): self
    {
        $this->stackTrace = $stacktrace;

        return $this;
    }

    /**
     * Return successful response
     *
     * @return JsonResponse
     */
    public function asSuccess(): JsonResponse
    {

        return $this->response->setData($this->payload);

    }

    /**
     * Return failed response
     *
     * @return JsonResponse
     */
    public function asFailed(): JsonResponse
    {

        if (config('app.debug'))
            $this->withMeta(['stack_trace' => $this->stackTrace]);

        if ($this->code)
            $this->withMeta(['error_code', $this->code]);


        return $this->response->setData($this->payload)
            ->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    }

}
