#!/usr/bin/env bash

vendor/shopware/platform/bin/phpstan.phar analyze --level 5 --configuration vendor/shopware/platform/phpstan.neon platform/src
