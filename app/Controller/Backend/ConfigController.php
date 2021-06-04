<?php

declare(strict_types = 1);

namespace App\Controller\Backend;

use App\Common\ResponseTools;
use App\Error\Backend\ConfigError;
use App\Request\Backend\ConfigRequest;
use App\Service\ConfigService;
use App\Service\MemberService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;

class ConfigController
{
    use ResponseTools;

    /**
     * @Inject()
     * @var ConfigService
     */
    protected $configService;

    /**
     * @Inject()
     * @var MemberService
     */
    protected $memberService;

    public function createOrUpdate(ConfigRequest $request)
    {
        $params = $request->all();
        // 用户验证
        if (! $this->memberService->checkByAccessToken($params['access_token'])) {
            return $this->error(new ConfigError('ERR_NO_AUTH'));
        }

        Arr::set($params, 'request_time', $request->getServerParams()['request_time']);
        if (! $this->configService->createOrUpdate($params)) {
            return $this->error(new ConfigError('ERR_CREATE_FAILED'));
        }

        return $this->success();
    }
}
