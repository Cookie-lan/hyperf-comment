<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Common\ResponseTools;
use App\Error\Api\CommentError;
use App\Service\MemberService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\SimpleCache\InvalidArgumentException;

class CheckMemberMiddleware implements MiddlewareInterface
{
    use ResponseTools;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject()
     * @var MemberService
     */
    protected $memberService;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 参数获取
        if ('GET' === $request->getMethod()) {
            $params = $request->getQueryParams();
        } else {
            $params = $request->getParsedBody();
        }

        // 会员信息获取
        $memberData = $this->memberService->getByAccessToken((string) Arr::get($params, 'access_token'));
        if (! $memberData) {
            return $this->error(CommentError::ERR_NO_MEMBER);
        }

        // 将会员数据加入到请求参数中
        Arr::set($params, 'member_data', $memberData);
        $request = Context::override(ServerRequestInterface::class, function () use ($request, $params) {
            if ('GET' === $request->getMethod()) {
                return $request->withQueryParams($params);
            } else {
                return $request->withParsedBody($params);
            }
        });

        return $handler->handle($request);
    }
}