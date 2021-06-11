<?php

declare(strict_types = 1);

namespace App\Service;


use App\Dao\CommentDao;
use App\Error\Api\CommentError;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Context;

class CommentService
{
    /**
     * @Inject()
     * @var IdGeneratorInterface
     */
    protected $idGenerator;

    /**
     * @Inject()
     * @var CommentDao
     */
    protected $commentDao;

    /**
     * 评论列表获取
     *
     * @param array $data
     * @return array
     */
    public function getComments(array $data): array
    {


        return $this->commentDao->getComments([]);
    }

    /**
     * 评论创建
     *
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        $customerId = Arr::get($data, 'customer_id');
        $sourceType = Arr::get($data, 'source_type');
        $requestTime = Context::get('request_time');

        // 插入数据整合
        $insert = [
            'uni_id'              => $this->idGenerator->generate(),
            'parent_id'           => Arr::get($data, 'comment_id', 0),
            'content'             => Arr::get($data, 'content'),
            'content_type'        => Arr::get($data, 'content_type', 1),
            'source_type'         => $sourceType,
            'founder_id'          => Arr::get($data, 'member_data.id'),
            'founder_type'        => Arr::get($data, 'member_data.type'),
            'founder_name'        => Arr::get($data, 'member_data.name'),
            'founder_avatar'      => Arr::get($data, 'member_data.avatar'),
            'customer_id'         => $customerId,
            'status'              => Arr::get($data, 'config.is_default_audit', 0),
            'ip'                  => ip2long(Context::get('request_ip')),
            'create_time'         => $requestTime,
            'update_time'         => $requestTime,
            'address'             => Arr::get($data, 'address', ''),
            'lng'                 => Arr::get($data, 'lng', 0.0000000),
            'lat'                 => Arr::get($data, 'lat', 0.0000000),
            'pics'                => Arr::get($data, 'pics', ''),
            'videos'              => Arr::get($data, 'videos', ''),
            'audios'              => Arr::get($data, 'audios', ''),
            'v_like_num'          => Arr::get($data, 'config.virtual_like_rule.base_num', 0),
            'target_id'           => Arr::get($data, 'target_id'),
            'target_type_tag'     => Arr::get($data, 'target_type_tag'),
            'device_token'        => Arr::get($data, 'device_token', ''),
            'device_type'         => Arr::get($data, 'device_type', 0),
            'main_comment_id'     => Arr::get($data, 'main_comment_id', 0),
            'dialogue_id'         => Arr::get($data, 'dialogue_id', 0),
            'be_commenter_id'     => Arr::get($data, 'be_commenter_id', 0),
            'be_commenter_type'   => Arr::get($data, 'be_commenter_type', 0),
            'be_commenter_name'   => Arr::get($data, 'be_commenter_name', ''),
            'be_commenter_avatar' => Arr::get($data, 'be_commenter_avatar', ''),
        ];

        return $this->commentDao->create($insert);
    }

    /**
     * 评论创建验证
     *
     * @param array $data
     * @param array $config
     * @return array
     */
    public function createValidation(array $data, array $config): array
    {
        if ($parentId = Arr::get($data, 'comment_id')) {
            // 盖楼验证
            if (! Arr::get($config, 'is_open_building')) {
                return CommentError::ERR_NOT_SUPPORT_BUILDING;
            }

            // 评论是否存在验证
            if (! $this->commentDao->exists(['uni_id' => $parentId])) {
                return CommentError::ERR_COMMENT_NOT_EXISTS;
            }
        }

        // 内容长短验证
        $minLength = Arr::get($config, 'min_length');
        $maxLength = Arr::get($config, 'max_length');
        $contentLength = mb_strlen($data['content'], 'UTF-8');
        if ($minLength && $minLength > $contentLength) {
            return CommentError::ERR_CONTENT_TOO_SHORT;
        }

        if ($maxLength && $maxLength < $contentLength) {
            return CommentError::ERR_CONTENT_TOO_LONG;
        }

        // 灌水验证
        $createInterval = Arr::get($config, 'create_interval');
        if ($createInterval) {
            $where = [
                'customer_id'  => (int) Arr::get($data, 'customer_id'),
                'source_type'  => (int) Arr::get($data, 'source_type'),
                'founder_id'   => (int) Arr::get($data, 'member_data.id'),
                'founder_type' => (int) Arr::get($data, 'member_data.type'),
            ];
            $lastComment = $this->commentDao->getLatestComment($where, ['create_time']);
            if ($lastComment && Context::get('request_time') < $lastComment['create_time'] + $createInterval) {
                return CommentError::ERR_CREATE_TOO_FAST;
            }
        }

        return [];
    }

    /**
     * 获取个人评论列表
     *
     * @param array $data
     * @return array
     */
    public function getMyComments(array $data): array
    {
        $where = [
            'customer_id'  => (int) Arr::get($data, 'customer_id'),
            'source_type'  => (int) Arr::get($data, 'source_type'),
            'founder_id'   => (int) Arr::get($data, 'member_data.id'),
            'founder_type' => (int) Arr::get($data, 'member_data.type'),
        ];

        $page = (int) Arr::get($data, 'page', 1);
        $count = (int) Arr::get($data, 'count', 10);
        $offset = ($page - 1) * $count;

        $comments = $this->commentDao->getLimitComments($where, ['*'], $offset, $count);
        $total = $this->commentDao->getCommentsCount($where);
        $pageInfo = build_page_info($page, $count, $total);

        return [
            'comments'  => $comments,
            'page_info' => $pageInfo,
        ];
    }

    /**
     * 获取他人的评论
     *
     * @param array $data
     * @return array
     */
    public function getOtherComments(array $data): array
    {
        $where = [
            'customer_id'  => (int) Arr::get($data, 'customer_id'),
            'source_type'  => (int) Arr::get($data, 'source_type'),
            'founder_id'   => (int) Arr::get($data, 'member_id'),
            'founder_type' => (int) Arr::get($data, 'member_data.type'),
        ];

        $page = (int) Arr::get($data, 'page', 1);
        $count = (int) Arr::get($data, 'count', 10);
        $offset = ($page - 1) * $count;

        $comments = $this->commentDao->getLimitComments($where, ['*'], $offset, $count);
        $total = $this->commentDao->getCommentsCount($where);
        $pageInfo = build_page_info($page, $count, $total);

        return [
            'comments'  => $comments,
            'page_info' => $pageInfo,
        ];
    }
}