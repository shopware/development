#!/usr/bin/env bash
#DESCRIPTION: build administration for production and run assetic

npm run --prefix vendor/shopware/platform/src/Administration/Resources/administration/ build -- __APP_URL__
bin/console assets:install
