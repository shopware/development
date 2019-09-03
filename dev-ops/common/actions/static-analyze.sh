#!/usr/bin/env bash
#DESCRIPTION: Run static code analysis on core

php dev-ops/analyze/vendor/bin/phpstan analyze --configuration vendor/shopware/platform/phpstan.neon
php dev-ops/analyze/vendor/bin/psalm --config=vendor/shopware/platform/psalm.xml --threads=2 --show-info=false
