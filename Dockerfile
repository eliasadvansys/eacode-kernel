# Project: Eacode Kernel
# File: Dockerfile
# Purpose: Build the PHP/Apache runtime image for EAK.
# Author: Ilja Nosov <info@eacode.lv>
# Copyright (c) 2025 eacode.lv
# License: MIT
# Date: 2025-12-29

FROM php:8.2-apache

# Install Composer for dependency management.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Add tools needed for Composer downloads.
RUN apt-get update \
    && apt-get install -y git unzip \
    && rm -rf /var/lib/apt/lists/*

# Enable PDO MySQL extension.
RUN docker-php-ext-install pdo_mysql

# Serve the Slim front controller from /public.
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite

# Allow .htaccess overrides for the front controller rewrite.
RUN printf '%s\n' '<Directory /var/www/html/public>' '    AllowOverride All' '</Directory>' > /etc/apache2/conf-available/allowoverride.conf \
    && a2enconf allowoverride
