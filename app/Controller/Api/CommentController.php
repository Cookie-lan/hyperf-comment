<?php


namespace App\Controller\Api;


use App\Common\ResponseTools;
use App\Error\Api\CommentError;
use App\Request\Api\CommentRequest;
use App\Service\CommentService;
use Hyperf\Di\Annotation\Inject;

class CommentController
{
    use ResponseTools;

    /**
     * @Inject()
     * @var CommentService
     */
    protected $commentService;

    public function create(CommentRequest $request)
    {
        $result = $this->commentService->create($request->all());
        if (! $result) {
            return $this->success();
        }
        return $this->error(new CommentError('ERR_CREATE_FAILED'));
    }
}