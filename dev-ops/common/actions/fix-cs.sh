#!/usr/bin/env bash
#DESCRIPTION: fix code style in src folder

php dev-ops/analyze/vendor/bin/php-cs-fixer fix -v --config=vendor/shopware/platform/.php_cs.dist
