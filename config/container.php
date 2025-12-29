<?php

/**
 * Project: Eacode Kernel (EAK)
 * File: config/container.php
 * Purpose: Configure DI services and bindings.
 * Author: Ilja Nosov <info@eacode.lv>
 * Copyright (c) 2025 eacode.lv
 * License: MIT
 * Date: 2025-12-29
 */

declare(strict_types=1);

use Core\SlimMicroKernel\Repositories\TestItemRepository;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

$builder = new ContainerBuilder();

$builder->addDefinitions([
    // Shared PDO connection for repositories.
    PDO::class => static function (): PDO {
        return require __DIR__ . '/database.php';
    },
    // Repository wiring with PDO dependency.
    TestItemRepository::class => static function (ContainerInterface $container): TestItemRepository {
        return new TestItemRepository($container->get(PDO::class));
    },
]);

return $builder->build();
