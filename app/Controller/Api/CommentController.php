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
     */
    public function create(CommentRequest $request): PsrResponseInterface
    {
        $params = $request->all();

        // 配置获取
        $config = $this->getConfig((int) $params['customer_id'], (int) $params['source_type']);

        // 配置规则验证
        $validationError = $this->commentService->createValidation($params, $config);
        if ($validationError) {
            return $this->error($validationError);
        }

        Arr::set($params, 'config', $config);

        $result = $this->commentService->create($params);
        if (! $result) {
            // todo 评论创建成功后需要触发的事件
            return $this->error(CommentError::ERR_CREATE_FAILED);
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

    /**
     * 获取自己的评论列表
     *
     * @param CommentRequest $request
     * @return PsrResponseInterface
     */
    public function getOneComments(CommentRequest $request): PsrResponseInterface
    {
        $params = $request->all();

        return $this->success($this->commentService->getMyComments($params));
    }

    /**
     * 获取他人评论
     *
     * @param CommentRequest $request
     * @return PsrResponseInterface
     */
    public function getOtherComments(CommentRequest $request): PsrResponseInterface
    {
        $params = $request->all();

        return $this->success($this->commentService->getOtherComments($params));
    }

    /**
     * 内容对应的评论列表
     *
     * @param CommentRequest $request
     * @return PsrResponseInterface
     */
    public function getComments(CommentRequest $request): PsrResponseInterface
    {
        $params = $request->all();

        return $this->success($this->commentService->getComments($params));
    }

}