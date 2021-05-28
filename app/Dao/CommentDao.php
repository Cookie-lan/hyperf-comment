<?php


namespace App\Dao;


use App\Model\CommentModel;
use Hyperf\Di\Annotation\Inject;

class CommentDao
{
    /**
     * @Inject()
     * @var CommentModel
     */
    protected $commentModel;

    /**
     * 获取评论数据
     *
     * @param array $where
     * @param array $field
     * @return array
     */
    public function getComments(array $where, array $field = ['*']): array
    {
        return $this->commentModel::query()->where($where)->get($field)->toArray();
    }
}