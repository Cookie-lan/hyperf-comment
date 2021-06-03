<?php


namespace App\Controller\Api;


use App\Common\ResponseTools;
use App\Error\Api\CommentError;
use App\Request\Api\CommentRequest;
use App\Service\CommentService;
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
     * 评论创建
     *
     * @param CommentRequest $request
     * @return PsrResponseInterface
     */
    public function create(CommentRequest $request): PsrResponseInterface
    {
        $params = $request->all();
        Arr::set($params, 'request_time', $request->getServerParams()['request_time']);
        Arr::set($params, 'request_ip', get_ip($request->getServerParams()));

        $memberData = $this->memberService->getMemberByAccessToken(Arr::get($params, 'access_token'));
        Arr::set($params, 'founder_id', Arr::get($memberData, 'member_id'));
        Arr::set($params, 'founder_type', Arr::get($memberData, 'member_type'));
        Arr::set($params, 'founder_name', Arr::get($memberData, 'member_name'));
        Arr::set($params, 'founder_avatar', Arr::get($memberData, 'member_avatar'));

        $result = $this->commentService->create($params);
        if (! $result) {
            // todo 评论创建成功后需要触发的事件
            return $this->error(new CommentError('ERR_CREATE_FAILED'));
        }
        return $this->success();
    }


}