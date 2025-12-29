-- Project: Eacode Kernel (EAK)
-- File: db/migrations/001_create_test_items.sql
-- Purpose: Create test_items and seed sample data.
-- Author: Ilja Nosov <info@eacode.lv>
-- Copyright (c) 2025 eacode.lv
-- License: MIT
-- Date: 2025-12-29

CREATE TABLE test_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    value VARCHAR(255) NOT NULL
);

-- Seed initial data for quick testing.
INSERT INTO test_items (value)
VALUES ('foo'), ('bar'), ('baz');
