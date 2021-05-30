<?php

declare(strict_types=1);

namespace App\Controller\Backend;

use App\Common\ResponseTools;
use App\Request\Backend\CommentRequest;
use App\Service\CommentService;
use Hyperf\Di\Annotation\Inject;
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
     * 评论列表
     *
     * @param CommentRequest $request
     * @return PsrResponseInterface
     */
    public function list(CommentRequest $request): PsrResponseInterface
    {
        return $this->success($this->commentService->list());
    }
}
