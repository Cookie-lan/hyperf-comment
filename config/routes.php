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
        Router::addRoute('POST', 'create', [App\Controller\Api\CommentController::class, 'create'],
            ['middleware' => [App\Middleware\CheckMemberMiddleware::class]]);
        Router::addRoute('GET', 'show/my', [App\Controller\Api\CommentController::class, 'getMyComments'],
            ['middleware' => [App\Middleware\CheckMemberMiddleware::class]]);
        Router::addRoute('GET', 'show/other', [App\Controller\Api\CommentController::class, 'getOtherComments'],
            ['middleware' => [App\Middleware\CheckMemberMiddleware::class]]);
        Router::addRoute('GET', 'list', [App\Controller\Api\CommentController::class, 'getList']);
    });

    // 后台路由
    Router::addGroup('/', function () {
        Router::addRoute('GET', 'list', [App\Controller\Backend\CommentController::class, 'getList']);
        Router::addRoute('POST', 'config', [App\Controller\Backend\ConfigController::class, 'createOrUpdate']);
    });
});