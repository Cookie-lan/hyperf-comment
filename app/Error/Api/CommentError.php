<?php


namespace App\Error\Api;


use App\Kernel\Factory\ErrorFactory;

class CommentError extends ErrorFactory
{
    /**
     * 自定义错误标识添加
     */
    const ERR_NOT_SUPPORT_BUILDING = [20001, 'Not Support Building'];
    const ERR_COMMENT_NOT_EXISTS   = [20002, 'Comment Not Exists'];
}