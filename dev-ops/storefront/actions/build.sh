#!/usr/bin/env bash
#DESCRIPTION: Builds the project for production

bin/console bundle:dump
bin/console feature:dump
PROJECT_ROOT=__PROJECT_ROOT__/  npm --prefix vendor/shopware/platform/src/Storefront/Resources/app/storefront/ run production
bin/console assets:install
bin/console theme:compile
