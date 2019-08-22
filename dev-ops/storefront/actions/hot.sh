#!/usr/bin/env bash
#DESCRIPTION: Starts the hot module reloading server

bin/console theme:dump
APP_URL=__APP_URL__ PROJECT_ROOT=__PROJECT_ROOT__/  npm --prefix vendor/shopware/platform/src/Storefront/Resources/ run-script hot