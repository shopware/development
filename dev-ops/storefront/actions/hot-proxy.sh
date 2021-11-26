#!/usr/bin/env bash
#DESCRIPTION: Starts the hot module reloading server

bin/console theme:dump
bin/console feature:dump
APP_URL=__APP_URL__ STOREFRONT_PROXY_PORT=__STOREFRONT_PROXY_PORT__ PROJECT_ROOT=__PROJECT_ROOT__/ ESLINT_DISABLE=__ESLINT_DISABLE__ npm --prefix vendor/shopware/platform/src/Storefront/Resources/app/storefront/ run-script hot-proxy
