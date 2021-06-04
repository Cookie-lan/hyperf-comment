<?php

declare(strict_types = 1);

namespace App\Service;


use App\Dao\ConfigDao;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Codec\Json;

class ConfigService
{
    /**
     * @Inject()
     * @var ConfigDao
     */
    protected $configDao;

    /**
     * 配置创建
     *
     * @param array $data
     * @return bool
     */
    public function createOrUpdate(array $data): bool
    {
        $config = [
            'min_length'                => Arr::get($data, 'min_length', 0),
            'max_length'                => Arr::get($data, 'max_length', 0),
            'is_default_audit'          => Arr::get($data, 'is_default_audit', 0),
            'is_open_member_delete'     => Arr::get($data, 'is_open_member_delete', 0),
            'create_interval'           => Arr::get($data, 'create_interval', 0),
            'is_open_building'          => Arr::get($data, 'is_open_building', 0),
            'max_pic_num'               => Arr::get($data, 'max_pic_num', 0),
            'is_open_virtual_like_rule' => Arr::get($data, 'is_open_virtual_like_rule', 0),
            'virtual_like_rule'         => Arr::get($data, 'virtual_like_rule') ? Json::decode(Arr::get($data,
                'virtual_like_rule')) : '',
        ];

        $customerId = Arr::get($data, 'customer_id');
        $sourceType = Arr::get($data, 'source_type');
        $where = [
            'customer_id' => $customerId,
            'source_type' => $sourceType,
        ];
        $requestTime = Arr::get($data, 'request_time');

        if ($this->configDao->exists($where)) {
            // 创建即更新
            $update = [
                'config'      => Json::encode($config),
                'update_time' => $requestTime,
            ];

            return $this->configDao->update($where, $update);
        } else {
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
    }
}