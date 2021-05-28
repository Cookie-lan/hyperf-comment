<?php

declare(strict_types=1);

namespace App\Service\Backend;


use App\Dao\CommentDao;
use Hyperf\Di\Annotation\Inject;

class CommentService
{
    /**
     * @Inject()
     * @var CommentDao
     */
    protected $commentDao;

    public function list(): array
    {
        return $this->commentDao->getComments([]);
    }
}