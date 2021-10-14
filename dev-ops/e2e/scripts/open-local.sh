#!/usr/bin/env bash

# Constants
CYPRESS_ENV=$1
export CYPRESS_baseUrl=$2
export CYPRESS_shopwareRoot="${3:-"$PWD"}"
export CYPRESS_localUsage=true
export APP_ENV=e2e

printf "\nCypress environment: ${CYPRESS_ENV}\n"
printf "App-URL: ${CYPRESS_baseUrl}\n"

if [ $CYPRESS_ENV == "Recovery" ]; then
    cd "./platform/src/Recovery/Test/e2e"
else
    cd "./platform/src/$CYPRESS_ENV/Resources/app/$(echo $CYPRESS_ENV | tr '[:upper:]' '[:lower:]')/test/e2e" || exit
fi

# Start Cypress test runner
npm run open "${CYPRESS_PARAMS}"
