<?php


namespace App\Common;


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
     * @param int $code
     * @param string $message
     * @return PsrResponseInterface
     */
    public function error(int $code, string $message): PsrResponseInterface
    {
        $result = [
            'code'    => $code,
            'message' => $message,
            'data'    => [],
        ];
        return $this->response->json($result);
    }
}