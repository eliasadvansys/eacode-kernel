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
}
