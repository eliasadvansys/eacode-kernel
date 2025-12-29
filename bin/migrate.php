<?php

/**
 * Project: Eacode Kernel (EAK)
 * File: bin/migrate.php
 * Purpose: Run SQL migrations against the MariaDB database.
 * Author: Ilja Nosov <info@eacode.lv>
 * Copyright (c) 2025 eacode.lv
 * License: MIT
 * Date: 2025-12-29
 */

declare(strict_types=1);

$pdo = require __DIR__ . '/../config/database.php';

// Track applied migrations to avoid re-running them.
$pdo->exec(
    'CREATE TABLE IF NOT EXISTS migrations ('
    . 'id INT AUTO_INCREMENT PRIMARY KEY, '
    . 'filename VARCHAR(255) NOT NULL UNIQUE, '
    . 'applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
    . ')'
);

$applied = $pdo->query('SELECT filename FROM migrations')->fetchAll(PDO::FETCH_COLUMN) ?: [];
$appliedMap = array_fill_keys($applied, true);

$migrationsDir = __DIR__ . '/../db/migrations';
if (!is_dir($migrationsDir)) {
    fwrite(STDERR, "Migrations directory not found: {$migrationsDir}\n");
    exit(1);
}

$files = glob($migrationsDir . '/*.sql');
if ($files === false) {
    fwrite(STDERR, "Failed to read migrations directory: {$migrationsDir}\n");
    exit(1);
}

sort($files, SORT_STRING);

$appliedAny = false;
foreach ($files as $file) {
    $filename = basename($file);
    if (isset($appliedMap[$filename])) {
        continue;
    }

    $sql = trim((string) file_get_contents($file));
    if ($sql === '') {
        fwrite(STDERR, "Skipping empty migration: {$filename}\n");
        continue;
    }

    // Apply each migration in a transaction for safety.
    $pdo->beginTransaction();
    try {
        $pdo->exec($sql);
        $stmt = $pdo->prepare('INSERT INTO migrations (filename) VALUES (:filename)');
        $stmt->execute(['filename' => $filename]);
        if ($pdo->inTransaction()) {
            $pdo->commit();
        }
        $appliedAny = true;
        echo "Applied {$filename}\n";
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

if (!$appliedAny) {
    echo "No new migrations.\n";
}
