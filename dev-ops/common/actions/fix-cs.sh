#!/usr/bin/env bash
#DESCRIPTION: fix code style in src folder

if [ -e vendor/shopware/platform/easy-coding-standard.yml ]; then php dev-ops/analyze/vendor/bin/ecs check --fix vendor/shopware/platform/src --config vendor/shopware/platform/easy-coding-standard.yml; else php dev-ops/analyze/vendor/bin/php-cs-fixer fix -v --config=vendor/shopware/platform/.php_cs.dist; fi
