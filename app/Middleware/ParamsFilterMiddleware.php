<?php

declare(strict_types = 1);

namespace App\Middleware;


use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ParamsFilterMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 参数获取
        if ('GET' === $request->getMethod()) {
            $params = $request->getQueryParams();
        } else {
            $params = $request->getParsedBody();
        }

        if ($params) {
            // xss过滤
            $this->filterXss($params);

            // 过滤后的参数重新写入
            $request = Context::override(ServerRequestInterface::class, function () use ($request, $params) {
                if ('GET' === $request->getMethod()) {
                    return $request->withQueryParams($params);
                }
                return $request->withParsedBody($params);
            });
        }

        return $handler->handle($request);
    }

    /**
     * xss过滤
     *
     * @param array $params
     */
    protected function filterXss(array &$params)
    {
        foreach ($params as $key => $value) {
            if (is_string($value)) {
                $params[$key] = filter_xss($value);
            }
        }
    }
}