<?php

/**
 * Project: Eacode Kernel (EAK)
 * File: src/Api/TestItems.php
 * Purpose: Provide REST endpoints for test items.
 * Author: Ilja Nosov <info@eacode.lv>
 * Copyright (c) 2025 eacode.lv
 * License: MIT
 * Date: 2025-12-29
 */

declare(strict_types=1);

namespace Core\SlimMicroKernel\Api;

use Core\SlimMicroKernel\Repositories\TestItemRepository;
use PDOException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class TestItems
{
    private TestItemRepository $repository;

    public function __construct(TestItemRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // Validate the path id before accessing the repository.
        $idRaw = $args['id'] ?? '';
        if (!is_string($idRaw) || $idRaw === '' || !ctype_digit($idRaw) || (int) $idRaw < 1) {
            return $this->json($response, ['error' => 'Invalid id. Use a positive integer.'], 400);
        }

        $item = $this->repository->findById((int) $idRaw);
        if ($item === null) {
            return $this->json($response, ['error' => 'Not found'], 404);
        }

        return $this->json($response, $item->toArray(), 200);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Accept JSON or form-encoded payloads.
        $payload = $request->getParsedBody();
        if (!is_array($payload)) {
            $payload = json_decode((string) $request->getBody(), true);
        }

        $valueRaw = $payload['value'] ?? null;
        if (!is_string($valueRaw)) {
            return $this->json($response, ['error' => 'Invalid value. Use a non-empty string.'], 400);
        }

        $value = trim($valueRaw);
        if ($value === '') {
            return $this->json($response, ['error' => 'Invalid value. Use a non-empty string.'], 400);
        }

        // Pre-check for duplicates to provide a clear conflict response.
        $existing = $this->repository->findByValue($value);
        if ($existing !== null) {
            return $this->json($response, [
                'error' => 'Value already exists.',
                'existingId' => $existing->getId(),
            ], 409);
        }

        try {
            $item = $this->repository->create($value);
        } catch (PDOException $exception) {
            // Return a REST-friendly conflict when the unique index blocks the insert.
            if ($this->isUniqueViolation($exception)) {
                return $this->json($response, ['error' => 'Value already exists.'], 409);
            }

            throw $exception;
        }

        return $this->json($response, $item->toArray(), 201)
            ->withHeader('Location', '/api/test-items/' . $item->getId());
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // Validate the path id before applying the update.
        $idRaw = $args['id'] ?? '';
        if (!is_string($idRaw) || $idRaw === '' || !ctype_digit($idRaw) || (int) $idRaw < 1) {
            return $this->json($response, ['error' => 'Invalid id. Use a positive integer.'], 400);
        }

        // Accept JSON or form-encoded payloads.
        $payload = $request->getParsedBody();
        if (!is_array($payload)) {
            $payload = json_decode((string) $request->getBody(), true);
        }

        $valueRaw = $payload['value'] ?? null;
        if (!is_string($valueRaw)) {
            return $this->json($response, ['error' => 'Invalid value. Use a non-empty string.'], 400);
        }

        $value = trim($valueRaw);
        if ($value === '') {
            return $this->json($response, ['error' => 'Invalid value. Use a non-empty string.'], 400);
        }

        // Pre-check for duplicates to provide a clear conflict response.
        $existing = $this->repository->findByValue($value);
        if ($existing !== null && $existing->getId() !== (int) $idRaw) {
            return $this->json($response, [
                'error' => 'Value already exists.',
                'existingId' => $existing->getId(),
            ], 409);
        }

        try {
            $item = $this->repository->update((int) $idRaw, $value);
        } catch (PDOException $exception) {
            // Return a REST-friendly conflict when the unique index blocks the update.
            if ($this->isUniqueViolation($exception)) {
                return $this->json($response, ['error' => 'Value already exists.'], 409);
            }

            throw $exception;
        }
        if ($item === null) {
            return $this->json($response, ['error' => 'Not found'], 404);
        }

        return $this->json($response, $item->toArray(), 200);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // Validate the path id before deleting the record.
        $idRaw = $args['id'] ?? '';
        if (!is_string($idRaw) || $idRaw === '' || !ctype_digit($idRaw) || (int) $idRaw < 1) {
            return $this->json($response, ['error' => 'Invalid id. Use a positive integer.'], 400);
        }

        $deleted = $this->repository->delete((int) $idRaw);
        if (!$deleted) {
            return $this->json($response, ['error' => 'Not found'], 404);
        }

        return $this->json($response, ['id' => (int) $idRaw, 'deleted' => true], 200);
    }

    private function json(ResponseInterface $response, array $payload, int $status): ResponseInterface
    {
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES));

        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }

    private function isUniqueViolation(PDOException $exception): bool
    {
        // MySQL/MariaDB report duplicate key errors with SQLSTATE 23000.
        return $exception->getCode() === '23000';
    }
}
