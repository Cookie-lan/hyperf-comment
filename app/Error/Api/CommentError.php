<?php

declare(strict_types = 1);

namespace App\Error\Api;


use App\Error\ErrorFactory;

class CommentError extends ErrorFactory
{
    protected $identifies = [
        'ERR_CREATE_FAILED' => [10001, 'create failed']
    ];
}