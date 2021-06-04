<?php

declare(strict_types = 1);

namespace App\Controller\Api;


use App\Common\ResponseTools;
use App\Error\Api\CommentError;
use App\Request\Api\CommentRequest;
use App\Service\CommentService;
use App\Service\ConfigService;
use App\Service\MemberService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\SimpleCache\InvalidArgumentException;

class CommentController
{
    use ResponseTools;

    /**
     * @Inject()
     * @var CommentService
     */
    protected $commentService;

    /**
     * @Inject()
     * @var MemberService
     */
    protected $memberService;

    /**
     * @Inject()
     * @var ConfigService
     */
    protected $configService;

    /**
     * 评论创建
     *
     * @param CommentRequest $request
     * @return PsrResponseInterface
     * @throws InvalidArgumentException
     */
    public function create(CommentRequest $request): PsrResponseInterface
    {
        $params = $request->all();
        Arr::set($params, 'request_time', $request->getServerParams()['request_time']);
        Arr::set($params, 'request_ip', get_ip($request->getServerParams()));

        // 会员信息获取
        $memberData = $this->memberService->getByAccessToken($params['access_token']);
        if (! $memberData) {
            return $this->error(new CommentError('ERR_NO_MEMBER'));
        }
        Arr::set($params, 'member_data', $memberData);

        // 配置获取
        Arr::set($params, 'config', $this->getConfig((int) $params['customer_id'], (int) $params['source_type']));

        $result = $this->commentService->create($params);
        if (! $result) {
            // todo 评论创建成功后需要触发的事件
            return $this->error(new CommentError('ERR_CREATE_FAILED'));
        }
        return $this->success();
    }

    /**
     * 配置获取
     *
     * @param int $customerId
     * @param int $sourceType
     * @return array
     */
    protected function getConfig(int $customerId, int $sourceType): array
    {
        $config = $this->configService->get(['customer_id' => $customerId, 'source_type' => $sourceType], ['config']);
        if (! $config) {
            return [];
        }

        return $config['config'];
    }
}