<?php

declare(strict_types = 1);

namespace App\Error;


class ErrorFactory implements ErrorInterface
{
    /**
     * @var array
     */
    protected $identifies = [];

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $msg;

    /**
     * 通过标识定义错误码和错误信息
     *
     * ErrorFactory constructor.
     * @param string $identify
     */
    public function __construct(string $identify)
    {
        $this->code = $this->identifies[$identify][0] ?? 500;
        $this->msg = $this->identifies[$identify][1] ?? 'undefined';
    }

    /**
     * 获取错误码
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * 获取错误信息
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->msg;
    }
}