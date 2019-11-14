#!/usr/bin/env bash

composer global require hirak/prestissimo
composer install --no-interaction --optimize-autoloader --no-suggest --no-scripts
composer install --no-interaction --optimize-autoloader --no-suggest --working-dir=dev-ops/analyze
