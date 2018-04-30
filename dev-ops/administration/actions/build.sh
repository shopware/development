#!/usr/bin/env bash
#DESCRIPTION: build administration for production and run assetic

npm run --prefix vendor/shopware/platform/src/Administration/Resources/administration/ build
bin/console assets:install
