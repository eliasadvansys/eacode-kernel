-- Project: Eacode Kernel (EAK)
-- File: db/migrations/002_add_unique_index_test_items_value.sql
-- Purpose: Enforce unique values for test_items.value.
-- Author: Ilja Nosov <info@eacode.lv>
-- Copyright (c) 2025 eacode.lv
-- License: MIT
-- Date: 2025-12-29

ALTER TABLE test_items
    ADD UNIQUE KEY uq_test_items_value (value);
