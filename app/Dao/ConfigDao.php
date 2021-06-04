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

    public function update(array $where, array $data): bool
    {
        return (bool) $this->configModel::where($where)->update($data);
    }

    public function exists(array $where): bool
    {
        return (bool) $this->configModel::where($where)->exists();
    }
}