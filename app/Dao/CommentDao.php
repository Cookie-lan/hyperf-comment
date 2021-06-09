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

    /**
     * 评论创建
     *
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        return (bool) $this->commentModel::insert($data);
    }

    /**
     * 判断评论是否存在
     *
     * @param array $where
     * @return bool
     */
    public function exists(array $where): bool
    {
        return (bool) $this->commentModel::where($where)->exists();
    }
}