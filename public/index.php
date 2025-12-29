<?php

/**
 * Project: Eacode Kernel (EAK)
 * File: public/index.php
 * Purpose: Bootstrap the Slim app and handle HTTP requests.
 * Author: Ilja Nosov <info@eacode.lv>
 * Copyright (c) 2025 eacode.lv
 * License: MIT
 * Date: 2025-12-29
 */

declare(strict_types=1);

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Boot DI container before creating the Slim app.
$container = require __DIR__ . '/../config/container.php';
AppFactory::setContainer($container);
$app = AppFactory::create();

// Register application routes.
$registerRoutes = require __DIR__ . '/../config/routes.php';
$registerRoutes($app);

// Enable error middleware for local development.
$app->addErrorMiddleware(true, true, true);

$app->run();
