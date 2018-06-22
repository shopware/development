#!/usr/bin/env bash
#DESCRIPTION: Run phpstan on core

vendor/bin/phpstan analyze --level 1 --configuration phpstan.neon vendor/shopware/platform/src/Core