<?php

/**
 * Project: Eacode Kernel (EAK)
 * File: config/database.php
 * Purpose: Create a PDO connection using environment configuration.
 * Author: Ilja Nosov <info@eacode.lv>
 * Copyright (c) 2025 eacode.lv
 * License: MIT
 * Date: 2025-12-29
 */

declare(strict_types=1);

// Read connection settings from environment.
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

if ($name === false || $name === '' || $user === false || $user === '') {
    throw new RuntimeException('Database configuration is missing. Set DB_NAME and DB_USER (optionally DB_PASS, DB_HOST, DB_PORT).');
}

// Build the DSN with utf8mb4 defaults.
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);

// Create PDO with safe defaults for errors and fetch mode.
$pdo = new PDO($dsn, $user, $pass ?: '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

return $pdo;
