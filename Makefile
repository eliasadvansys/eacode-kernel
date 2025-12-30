# Project: Eacode Kernel (EAK)
# File: Makefile
# Purpose: Provide short helper commands for local development.
# Author: Ilja Nosov <info@eacode.lv>
# Copyright (c) 2025 eacode.lv
# License: MIT
# Date: 2025-12-30

COMPOSE ?= docker compose

.PHONY: up down restart logs shell migrate

up:
	$(COMPOSE) up -d --build

down:
	$(COMPOSE) down

restart: down up

logs:
	$(COMPOSE) logs -f --tail=200

shell:
	$(COMPOSE) exec web sh

migrate:
	$(COMPOSE) exec web php /var/www/html/bin/migrate.php
