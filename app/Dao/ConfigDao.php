<?php

declare(strict_types = 1);

namespace App\Dao;


use App\Model\ConfigModel;
use Hyperf\Di\Annotation\Inject;

class ConfigDao
{
    /**
     * @Inject()
     * @var ConfigModel
     */
    protected $configModel;

    /**
     * 配置创建
     *
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        return (bool) $this->configModel::insert($data);
    }

    /**
     * 配置更新
     *
     * @param array $where
     * @param array $data
     * @return bool
     */
    public function update(array $where, array $data): bool
    {
        return (bool) $this->configModel::where($where)->update($data);
    }

    /**
     * 查看配置是否存在
     *
     * @param array $where
     * @return bool
     */
    public function exists(array $where): bool
    {
        return (bool) $this->configModel::where($where)->exists();
    }

    /**
     * 配置获取
     *
     * @param array $where
     * @param array $field
     * @return array
     */
    public function get(array $where, array $field): array
    {
        return $this->configModel::where($where)->first($field)->toArray();
    }
}