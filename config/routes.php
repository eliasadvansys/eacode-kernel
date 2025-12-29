<?php

/**
 * Project: Eacode Kernel (EAK)
 * File: config/routes.php
 * Purpose: Define application routes.
 * Author: Ilja Nosov <info@eacode.lv>
 * Copyright (c) 2025 eacode.lv
 * License: MIT
 * Date: 2025-12-29
 */

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Core\SlimMicroKernel\Api\Ping;
use Core\SlimMicroKernel\Api\TestItem;
use Core\SlimMicroKernel\Controllers\TestController;

return function (App $app): void {
    // API routes stay grouped under /api.
    $app->group('/api', function (RouteCollectorProxy $group): void {
        $group->get('/ping', Ping::class);
        $group->get('/test/{id}', TestItem::class);
    });
    // Test routes used for quick validation during development.
    $app->get('/test', TestController::class);
    $app->get('/test/db/{id}', TestController::class . ':db');
};
