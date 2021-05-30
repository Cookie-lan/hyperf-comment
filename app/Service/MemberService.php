<?php

declare(strict_types = 1);

namespace App\Service;


class MemberService
{
    public function getMemberByAccessToken(string $accessToken): array
    {
        // todo 对接会员服务，获取会员信息
        return [
            'member_id' => 1,
            'member_name' => '测试',
            'member_avatar' => '',
            'member_type' => 1
        ];
    }
}