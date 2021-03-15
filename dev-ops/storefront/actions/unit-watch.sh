#!/usr/bin/env bash
#DESCRIPTION: runs & auto watches storefront unit tests

TTY: PROJECT_ROOT=__PROJECT_ROOT__ npm --prefix vendor/shopware/platform/src/Storefront/Resources/app/storefront/ run unit-watch
