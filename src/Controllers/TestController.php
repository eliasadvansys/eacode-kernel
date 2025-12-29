<?php

/**
 * Project: Eacode Kernel (EAK)
 * File: src/Controllers/TestController.php
 * Purpose: Serve test endpoints for EAK.
 * Author: Ilja Nosov <info@eacode.lv>
 * Copyright (c) 2025 eacode.lv
 * License: MIT
 * Date: 2025-12-29
 */

declare(strict_types=1);

namespace Core\SlimMicroKernel\Controllers;

use Core\SlimMicroKernel\Repositories\TestItemRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class TestController
{
    private TestItemRepository $repository;

    public function __construct(TestItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Plain-text response for quick health checks.
        $response->getBody()->write('OK');

        return $response->withHeader('Content-Type', 'text/plain');
    }

    public function db(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $idRaw = $args['id'] ?? '';
        // Validate that the id is a positive integer.
        if (!is_string($idRaw) || $idRaw === '' || !ctype_digit($idRaw) || (int) $idRaw < 1) {
            return $this->json($response, ['error' => 'Invalid id. Use a positive integer.'], 400);
        }

        $item = $this->repository->findById((int) $idRaw);
        if ($item === null) {
            return $this->json($response, ['error' => 'Not found'], 404);
        }

        // Return the model as a JSON payload.
        return $this->json($response, $item->toArray(), 200);
    }

    private function json(ResponseInterface $response, array $payload, int $status): ResponseInterface
    {
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES));

        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}
