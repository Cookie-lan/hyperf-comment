<?php

declare(strict_types = 1);

namespace App\Middleware;


use Hyperf\HttpServer\Request;
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
        // 设置请求时间和请求ip至协程上下文中
        $serverParams = $request->getServerParams();
        Context::set('request_time', $serverParams['request_time']);
        Context::set('request_ip', get_ip($serverParams));

        // 参数获取
        if ('GET' === $request->getMethod()) {
            $params = $request->getQueryParams();
        } else {
            $params = $request->getParsedBody();
        }

        if ($params) {
            // html标签转义
            $this->encodeHTML($params);
            if ('GET' === $request->getMethod()) {
                $request = $request->withQueryParams($params);
            } else {
                $request = $request->withParsedBody($params);
            }

            // 过滤后的参数重新写入request
            Context::set(ServerRequestInterface::class, $request);

            // 清理上下文中数据的缓存
            $this->container->get(Request::class)->clearStoredParsedData();
        }

        return $handler->handle($request);
        // html标签反转义，用于查看原数据
        //        $body = $this->decodeHTML($response->getBody()->getContents());
        //        return $response->withBody(new SwooleStream($body));
    }

    /**
     * html标签转义
     *
     * @param array $params
     */
    protected function encodeHTML(array &$params)
    {
        foreach ($params as $key => $value) {
            if (is_string($value) && strip_tags($value) != $value) {
                // 如果输入的内容有html标签，则转义
                $params[$key] = htmlspecialchars($value, ENT_QUOTES);
            }
        }
    }

    /**
     * html标签反转义
     *
     * @param string $content
     * @return string
     */
    protected function decodeHTML(string $content)
    {
        return htmlspecialchars_decode($content);
    }
}