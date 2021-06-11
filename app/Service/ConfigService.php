<?php

declare(strict_types = 1);

namespace App\Service;


use App\Dao\ConfigDao;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Context;

class ConfigService
{
    /**
     * @Inject()
     * @var ConfigDao
     */
    protected $configDao;

    /**
     * @Inject()
     * @var CommentService
     */
    protected $commentService;

    /**
     * 配置创建
     *
     * @param array $data
     * @return bool
     */
    public function createOrUpdate(array $data): bool
    {
        $config = [
            'min_length'                => (int) Arr::get($data, 'min_length', 0),
            'max_length'                => (int) Arr::get($data, 'max_length', 0),
            'is_default_audit'          => (int) Arr::get($data, 'is_default_audit', 0),
            'is_open_member_delete'     => (int) Arr::get($data, 'is_open_member_delete', 0),
            'create_interval'           => (int) Arr::get($data, 'create_interval', 0),
            'is_open_building'          => (int) Arr::get($data, 'is_open_building', 0),
            'max_pic_num'               => (int) Arr::get($data, 'max_pic_num', 0),
            'is_open_virtual_like_rule' => (int) Arr::get($data, 'is_open_virtual_like_rule', 0),
            'virtual_like_rule'         => Arr::get($data, 'virtual_like_rule') ? Json::decode(Arr::get($data,
                'virtual_like_rule')) : [],
        ];

        $customerId = (int) Arr::get($data, 'customer_id');
        $sourceType = (int) Arr::get($data, 'source_type');
        $where = [
            'customer_id' => $customerId,
            'source_type' => $sourceType,
        ];
        $requestTime = Context::get('request_time');

        if ($this->configDao->exists($where)) {
            // 存在即更新
            $update = [
                'config'      => Json::encode($config),
                'update_time' => $requestTime,
            ];

            return $this->configDao->update($where, $update);
        }

        // 不存在即创建
        $insert = [
            'customer_id' => $customerId,
            'source_type' => $sourceType,
            'config'      => Json::encode($config),
            'create_time' => $requestTime,
            'update_time' => $requestTime,
        ];

        return $this->configDao->create($insert);
    }

    /**
     * 获取配置
     *
     * @param array $where
     * @param array $field
     * @return array
     */
    public function get(array $where, array $field = ['*']): array
    {
        $config = $this->configDao->get($where, $field);
        if (! $config) {
            return [];
        }

        // 虚拟点赞量规则处理
        if (! Arr::get($config, 'is_open_virtual_like_rule')) {
            $config['virtual_like_rule'] = [];
        }

        return $config;
    }
}