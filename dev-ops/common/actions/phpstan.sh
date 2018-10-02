#!/usr/bin/env bash
#DESCRIPTION: Run phpstan on core

vendor/shopware/platform/bin/phpstan.phar analyze --level 5 --configuration vendor/shopware/platform/phpstan.neon vendor/shopware/platform/src
