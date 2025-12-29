<!--
Project: Eacode Kernel
File: README.md
Purpose: Project overview and quick start instructions.
Author: Ilja Nosov <info@eacode.lv>
Copyright (c) 2025 eacode.lv
License: MIT
Date: 2025-12-29
-->

# Eacode Kernel

Eacode Kernel is a lightweight PHP micro kernel based on Slim 4. It provides a minimal HTTP bootstrap, simple controllers, and a tiny migration runner for MariaDB.

## Requirements
- PHP 8.2+
- Docker + Docker Compose (recommended for local development)

## Quick Start (Docker)
```bash
docker compose up -d --build
```

Open:
- `http://localhost:8181/test` → `OK`
- `http://localhost:8181/test/db/1` → JSON row from `test_items`
- `http://localhost:8181/api/ping` → `pong`

## Database
The web container connects to MariaDB using environment variables from `docker-compose.yml`:
- DB name: `eak`
- user: `user`
- password: `qwerty`

## Migrations
Run migrations manually (inside container or locally with env vars):
```bash
php bin/migrate.php
```

## Project Structure
- `public/` front controller (`index.php`) and rewrite rules
- `config/` routes, DI container, DB settings
- `src/` controllers, API handlers, models, repositories
- `db/migrations/` SQL migrations

## License
MIT. Slim and its dependencies remain under their respective licenses.
