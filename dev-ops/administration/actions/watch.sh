#!/usr/bin/env bash
#DESCRIPTION: starting administration dev server for hot module reloading. Disable linting with the parameter ESLINT_DISABLE=true

bin/console bundle:dump
bin/console feature:dump
PROJECT_ROOT=__PROJECT_ROOT__ PORT=__DEVPORT__ HOST=__HOST__ ESLINT_DISABLE=__ESLINT_DISABLE__ ENV_FILE=__PROJECT_ROOT__/.env APP_URL=__APP_URL__ npm run --prefix vendor/shopware/platform/src/Administration/Resources/app/administration/ dev
