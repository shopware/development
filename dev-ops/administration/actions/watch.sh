#!/usr/bin/env bash
#DESCRIPTION: starting administration dev server for hot module reloading. Disable linting with the parameter ESLINT_DISABLE=true

bin/console administration:dump:plugins
PORT=__DEVPORT__ ESLINT_DISABLE=__ESLINT_DISABLE__ npm run --prefix vendor/shopware/platform/src/Administration/Resources/administration/ dev -- __APP_URL__
