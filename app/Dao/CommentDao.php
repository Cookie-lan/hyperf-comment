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
     * 获取多条评论
     *
     * @param array $where
     * @param array $fields
     * @return array
     */
    public function getComments(array $where, array $fields = ['*']): array
    {
        return $this->commentModel::query()->where($where)->get($fields)->toArray();
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

    /**
     * 获取最新的单条评论
     *
     * @param array $where
     * @param array $fields
     * @return array
     */
    public function getLatestComment(array $where, array $fields = ['*']): array
    {
        return $this->commentModel::where($where)->latest('id')->first($fields)->toArray();
    }

    /**
     * 获取分页评论
     *
     * @param array $where
     * @param array $fields
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getLimitComments(array $where, array $fields = ['*'], int $offset = 0, int $limit = 10): array
    {
        return $this->commentModel::where($where)->offset($offset)->limit($limit)->get($fields)->toArray();
    }

    /**
     * 获取评论数量
     *
     * @param array $where
     * @return int
     */
    public function getCommentsCount(array $where): int
    {
        return $this->commentModel::where($where)->count();
    }
}