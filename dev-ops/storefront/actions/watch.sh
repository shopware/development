#!/usr/bin/env bash
#DESCRIPTION: Starts the webpack watcher

bin/console bundle:dump
APP_URL=__APP_URL__ PROJECT_ROOT=__PROJECT_ROOT__/  npm --prefix vendor/shopware/platform/src/Storefront/Resources/app/storefront/ run watch
