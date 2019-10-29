#!/usr/bin/env bash
#DESCRIPTION: Builds the project for development

bin/console bundle:dump
PROJECT_ROOT=__PROJECT_ROOT__/ npm --prefix vendor/shopware/platform/src/Storefront/Resources/app/storefront/ run development
bin/console theme:compile
bin/console assets:install