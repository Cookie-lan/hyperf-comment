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
     * @return array
     */
    public function list(): array
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
     * 根据条件判断评论是否存在
     *
     * @param array $where
     * @return bool
     */
    public function exists(array $where): bool
    {
        return $this->commentDao->exists($where);
    }

    public function createValidation(array $params, array $config)
    {
        if ($parentId = Arr::get($params, 'comment_id')) {
            // 盖楼验证
            if (! Arr::get($config, 'is_open_building')) {
                return CommentError::ERR_NOT_SUPPORT_BUILDING;
            }

            // 评论是否存在验证
            if (! $this->commentDao->exists(['uni_id' => $parentId])) {
                return CommentError::ERR_COMMENT_NOT_EXISTS;
            }

            // 内容长短验证
            $minLength = Arr::get($config, 'min_length');
            $maxLength = Arr::get($config, 'max_length');
            if ($minLength && $minLength > mb_strlen($params['content'], 'UTF-8')) {
                return 'ERR_';
            }
        }
    }
}