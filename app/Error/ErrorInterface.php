<?php

declare(strict_types = 1);

namespace App\Error;


interface ErrorInterface
{
    /**
     * 获取错误码
     *
     * @return int
     */
    public function getCode(): int;

    /**
     * 获取错误信息
     *
     * @return string
     */
    public function getMessage(): string;
}