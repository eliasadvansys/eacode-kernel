<?php

/**
 * Project: Eacode Kernel (EAK)
 * File: src/Models/TestItem.php
 * Purpose: Model for a test item.
 * Author: Ilja Nosov <info@eacode.lv>
 * Copyright (c) 2025 eacode.lv
 * License: MIT
 * Date: 2025-12-29
 */

declare(strict_types=1);

namespace Core\SlimMicroKernel\Models;

final class TestItem
{
    private int $id;
    private string $value;

    public function __construct(int $id, string $value)
    {
        // Simple immutable value object.
        $this->id = $id;
        $this->value = $value;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
        ];
    }
}
