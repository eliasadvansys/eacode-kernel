<?php

/**
 * Project: Eacode Kernel (EAK)
 * File: src/Api/Ping.php
 * Purpose: Provide the API ping endpoint.
 * Author: Ilja Nosov <info@eacode.lv>
 * Copyright (c) 2025 eacode.lv
 * License: MIT
 * Date: 2025-12-29
 */

declare(strict_types=1);

namespace Core\SlimMicroKernel\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Ping
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Minimal liveness response.
        $response->getBody()->write('pong');

        return $response->withHeader('Content-Type', 'text/plain');
    }
}
