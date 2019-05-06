#!/usr/bin/env bash
#DESCRIPTION: starting administration dev server for hot module reloading. Disable linting with the parameter ESLINT_DISABLE=true

bin/console administration:dump:bundles
PROJECT_ROOT=__PROJECT_ROOT__ APP_ENV=__E2E_ENV__ PORT=__DEVPORT__ HOST=__HOST__ ESLINT_DISABLE=__ESLINT_DISABLE__ ENV_FILE=__PROJECT_ROOT__/.env npm run --prefix vendor/shopware/platform/src/Administration/Resources/administration/ dev -- __APP_URL__
