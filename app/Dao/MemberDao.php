<?php

declare(strict_types = 1);

namespace App\Dao;


use Hyperf\Di\Annotation\Inject;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class MemberDao
{
    const MEMBER_CACHE_TTL    = 3600; // 会员缓存过期时间，单位秒
    const MEMBER_CACHE_PREFIX = 'comment:member:';

    /**
     * @Inject()
     * @var CacheInterface
     */
    protected $cache;

    /**
     * 会员缓存获取
     *
     * @param string $accessToken
     * @return array
     * @throws InvalidArgumentException
     */
    public function getByAccessToken(string $accessToken): array
    {
        return $this->cache->get(self::MEMBER_CACHE_PREFIX . $accessToken, []);
    }

    /**
     * 会员缓存设置
     *
     * @param string $accessToken
     * @param array $data
     * @throws InvalidArgumentException
     */
    public function setByAccessToken(string $accessToken, array $data)
    {
        $this->cache->set(self::MEMBER_CACHE_PREFIX . $accessToken, $data, self::MEMBER_CACHE_TTL);
    }
}