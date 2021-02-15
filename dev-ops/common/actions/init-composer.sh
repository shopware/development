#!/usr/bin/env bash

rm -rf vendor/shopware
rm -rf composer.lock
rm -rf dev-ops/analyze/vendor

composer update --no-interaction --optimize-autoloader --no-suggest --no-scripts
composer install --no-interaction --optimize-autoloader --no-suggest --working-dir=dev-ops/analyze
