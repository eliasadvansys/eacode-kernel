<!--
Project: Eacode Kernel
File: CONTRIBUTING.md
Purpose: Contribution guidelines for EAK.
Author: Ilja Nosov <info@eacode.lv>
Copyright (c) 2025 eacode.lv
License: MIT
Date: 2025-12-29
-->

# Contributing

Thanks for helping improve EAK. Please keep changes small and focused.

## Development
- Use Docker: `docker compose up -d --build`
- Run migrations: `php bin/migrate.php`

## Code Style
- PHP 8.2+, PSR-12, `declare(strict_types=1)`
- Comments must be in English
- New files must start with the standard project header

## Commits & PRs
- Use concise, imperative commit messages
- Explain behavior changes and add reproduction steps if relevant
