<?php

declare(strict_types = 1);

namespace App\Error;


abstract class ErrorFactory implements ErrorInterface
{
    /**
     * 公共错误标识
     *
     * @var array
     */
    protected $identifies = [
        'ERR_NO_MEMBER'     => [10001, 'No Member'],
        'ERR_CREATE_FAILED' => [10002, 'Create Failed'],
        'ERR_NO_AUTH'       => [10003, 'No Auth'],
    ];

    /**
     * 错误码
     *
     * @var int
     */
    protected $code;

    /**
     * 错误信息
     *
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
        $this->init();
        $this->code = $this->identifies[$identify][0] ?? 500;
        $this->msg = $this->identifies[$identify][1] ?? 'Undefined';
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