#!/usr/bin/env bash
#DESCRIPTION: fix code style in src folder

php ./vendor/shopware/platform/bin/php-cs-fixer.phar fix -v --config=vendor/shopware/platform/.php_cs.dist vendor/shopware/platform/src src
