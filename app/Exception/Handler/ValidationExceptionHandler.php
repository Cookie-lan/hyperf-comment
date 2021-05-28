<?php


namespace App\Exception\Handler;


use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ValidationExceptionHandler extends ExceptionHandler
{

    /**
     * Handle the exception, and return the specified result.
     *
     * @param Throwable $throwable
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 阻止冒泡
        $this->stopPropagation();

        /** @var ValidationException $throwable */
        $result = [
            'code'    => 400,
            'message' => $throwable->validator->errors()->first(),
            'data'    => [],
        ];
        return $response->withAddedHeader('content-type', 'application/json; charset=utf-8')
                        ->withStatus(400)
                        ->withBody(new SwooleStream(Json::encode($result)));
    }

    /**
     * Determine if the current exception handler should handle the exception,.
     *
     * @param Throwable $throwable
     * @return bool
     *              If return true, then this exception handler will handle the exception,
     *              If return false, then delegate to next handler
     */
    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}