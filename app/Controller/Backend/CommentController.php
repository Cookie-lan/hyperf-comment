<?php

declare(strict_types=1);

namespace App\Controller\Backend;

use App\Common\ResponseTools;
use App\Service\Backend\CommentService;
use Hyperf\Di\Annotation\Inject;

class CommentController
{
    use ResponseTools;

    /**
     * @Inject()
     * @var CommentService
     */
    protected $commentService;

    public function list()
    {
        return $this->success($this->commentService->list());
    }
}
