<?php

declare(strict_types = 1);

namespace App\Kernel\Factory;


abstract class ErrorFactory
{
    /**
     * 公共错误标识
     */
    const ERR_NO_MEMBER = [10001, 'No Member'];
    const ERR_CREATE_FAILED = [10002, 'Create Failed'];
    const ERR_NO_AUTH = [10003, 'No Auth'];
}