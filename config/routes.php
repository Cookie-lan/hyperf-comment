<?php

declare(strict_types = 1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addGroup('/v1', function () {
    // 前台路由
    Router::addGroup('/api/', function () {
        Router::addRoute('POST', 'create', [App\Controller\Api\CommentController::class, 'create']);
    });

    // 后台路由
    Router::addGroup('/', function () {
        Router::addRoute('GET', 'list', [App\Controller\Backend\CommentController::class, 'list']);
    });
});