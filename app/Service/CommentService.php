<?php

declare(strict_types=1);

namespace App\Service;


use App\Dao\CommentDao;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Utils\Arr;

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
        $insert = [
            'uni_id'              => $this->idGenerator->generate(),
            'parent_id'           => Arr::get($data, 'parent_id', '0'),
            'content'             => Arr::get($data, 'content'),
            'content_type'        => Arr::get($data, 'content_type', 1),
            'source_type'         => Arr::get($data, 'source_type'),
            'founder_id'          => Arr::get($data, 'member_data.id'),
            'founder_type'        => Arr::get($data, 'member_data.type'),
            'founder_name'        => Arr::get($data, 'member_data.name'),
            'founder_avatar'      => Arr::get($data, 'member_data.avatar'),
            'customer_id'         => Arr::get($data, 'customer_id'),
            'status'              => 0,
            'ip'                  => ip2long(Arr::get($data, 'request_ip')),
            'create_time'         => Arr::get($data, 'request_time'),
            'update_time'         => Arr::get($data, 'request_time'),
            'address'             => Arr::get($data, 'address', ''),
            'lng'                 => Arr::get($data, 'lng', 0.0000000),
            'lat'                 => Arr::get($data, 'lat', 0.0000000),
            'pics'                => Arr::get($data, 'pics', ''),
            'videos'              => Arr::get($data, 'videos', ''),
            'audios'              => Arr::get($data, 'audios', ''),
            'like_num'            => 0,
            'v_like_num'          => 0,
            'n_like_num'          => 0,
            'target_id'           => 0,
            'target_type_tag'     => 'post',
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
}