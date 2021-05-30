<?php

declare(strict_types=1);

namespace App\Controller\Backend;

use App\Common\ResponseTools;
use App\Request\Backend\ConfigRequest;
use App\Service\ConfigService;
use Hyperf\Di\Annotation\Inject;

class ConfigController
{
    use ResponseTools;

    /**
     * @Inject()
     * @var ConfigService
     */
    protected $configService;

    public function create(ConfigRequest $request)
    {
        return $this->success();
    }
}
