#!/usr/bin/env bash
#DESCRIPTION: build administration for production and run assetic

bin/console administration:dump:bundles
PROJECT_ROOT=__PROJECT_ROOT__ npm run --prefix vendor/shopware/platform/src/Administration/Resources/administration/ build
bin/console assets:install
