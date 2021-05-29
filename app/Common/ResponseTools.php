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
     * @param ErrorFactory $errorFactory
     * @return PsrResponseInterface
     */
    public function error(ErrorFactory $errorFactory): PsrResponseInterface
    {
        $result = [
            'code'    => $errorFactory->getCode(),
            'message' => trans('errors.' . $errorFactory->getMessage()),
            'data'    => [],
        ];
        return $this->response->json($result);
    }
}