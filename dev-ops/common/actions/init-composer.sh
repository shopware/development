#!/usr/bin/env bash

composer install --no-interaction --optimize-autoloader --no-suggest --no-scripts
composer install --no-interaction --optimize-autoloader --no-suggest --working-dir=dev-ops/analyze
