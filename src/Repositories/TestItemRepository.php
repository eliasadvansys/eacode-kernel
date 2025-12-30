<?php

/**
 * Project: Eacode Kernel (EAK)
 * File: src/Repositories/TestItemRepository.php
 * Purpose: Data access for test_items.
 * Author: Ilja Nosov <info@eacode.lv>
 * Copyright (c) 2025 eacode.lv
 * License: MIT
 * Date: 2025-12-29
 */

declare(strict_types=1);

namespace Core\SlimMicroKernel\Repositories;

use Core\SlimMicroKernel\Models\TestItem;
use PDO;

final class TestItemRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?TestItem
    {
        // Parameterized query to avoid SQL injection.
        $stmt = $this->pdo->prepare('SELECT id, value FROM test_items WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        return new TestItem((int) $row['id'], (string) $row['value']);
    }

    public function findByValue(string $value): ?TestItem
    {
        // Look up by value to support unique checks before inserts/updates.
        $stmt = $this->pdo->prepare('SELECT id, value FROM test_items WHERE value = :value');
        $stmt->execute(['value' => $value]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        return new TestItem((int) $row['id'], (string) $row['value']);
    }

    public function create(string $value): TestItem
    {
        $stmt = $this->pdo->prepare('INSERT INTO test_items (value) VALUES (:value)');
        $stmt->execute(['value' => $value]);

        return new TestItem((int) $this->pdo->lastInsertId(), $value);
    }

    public function update(int $id, string $value): ?TestItem
    {
        // Return null when no rows are affected.
        $stmt = $this->pdo->prepare('UPDATE test_items SET value = :value WHERE id = :id');
        $stmt->execute(['id' => $id, 'value' => $value]);

        if ($stmt->rowCount() === 0) {
            return null;
        }

        return new TestItem($id, $value);
    }

    public function delete(int $id): bool
    {
        // Return true only when a row is actually deleted.
        $stmt = $this->pdo->prepare('DELETE FROM test_items WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
