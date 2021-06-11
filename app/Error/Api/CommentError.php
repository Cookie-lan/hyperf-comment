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
    const ERR_CONTENT_TOO_SHORT    = [20003, 'Content Too Short'];
    const ERR_CONTENT_TOO_LONG     = [20004, 'Content Too Long'];
    const ERR_CREATE_TOO_FAST      = [20005, 'Create Too Fast'];
}