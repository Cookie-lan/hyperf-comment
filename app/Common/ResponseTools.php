<?php


namespace App\Common;


use App\Error\ErrorFactory;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

trait ResponseTools
{
    /**
     * @Inject()
     * @var ResponseInterface
     */
    protected $response;

    /**
     * 成功响应数据
     *
     * @param array $data
     * @return PsrResponseInterface
     */
    public function success(array $data = []): PsrResponseInterface
    {
        $result = [
            'code'    => 0,
            'message' => 'success',
            'data'    => $data,
        ];
        return $this->response->json($result);
    }

    /**
     * 失败响应数据
     *
     * @param array $errorInfo
     * @return PsrResponseInterface
     */
    public function error(array $errorInfo): PsrResponseInterface
    {
        $result = [
            'code'    => $errorInfo[0] ?? 500,
            'message' => trans('errors.' . ($errorInfo[1] ?? 'Undefined')),
            'data'    => [],
        ];
        return $this->response->json($result);
    }
}