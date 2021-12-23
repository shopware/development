#!/usr/bin/env sh

# Constants
export CYPRESS_baseUrl=$1
export CYPRESS_shopwareRoot="${2:-"$PWD"}"
CYPRESS_PARAMS=$3
export APP_ENV=e2e
export CYPRESS_localUsage=true

printf "App-URL: ${CYPRESS_baseUrl}\n"

# Prepare Shopware
bin/console theme:change --all Storefront
bin/console cache:clear
bin/console e2e:dump-db

# Start Cypress in CLI
cd "./platform/tests/e2e"

./node_modules/.bin/cypress run $CYPRESS_PARAMS
