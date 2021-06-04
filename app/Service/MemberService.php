<?php

declare(strict_types = 1);

namespace App\Service;


use App\Dao\MemberDao;
use Hyperf\Di\Annotation\Inject;
use Psr\SimpleCache\InvalidArgumentException;

class MemberService
{
    /**
     * @Inject()
     * @var MemberDao
     */
    protected $memberDao;

    /**
     * 通过access_token获取会员数据
     *
     * @param string $accessToken
     * @return array
     * @throws InvalidArgumentException
     */
    public function getByAccessToken(string $accessToken): array
    {
        // 缓存获取
        $memberData = $this->memberDao->getByAccessToken($accessToken);
        if ($memberData) {
            return $memberData;
        }

        // todo 对接会员服务，获取会员信息
        $memberData = [
            'id'     => 1,
            'name'   => '测试',
            'avatar' => '',
            'type'   => 1
        ];

        // 缓存设置
        $this->memberDao->setByAccessToken($accessToken, $memberData);

        return $memberData;
    }

    public function checkByAccessToken(string $accessToken): bool
    {
        // todo 对接会员服务，判断用户是否存在
        return true;
    }
}