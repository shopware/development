#!/usr/bin/env bash

# Constants
CONTAINER=$1
CYPRESS_ENV=$2
APP_URL=$3
CYPRESS_PARAMS=$4

printf "\nCypress environment: ${CYPRESS_ENV}\n"
printf "App-URL: ${APP_URL}\n\n"

# Start express server for docker usage
docker exec -u "$USER_KEY" "$CONTAINER" forever start vendor/shopware/platform/src/Administration/Resources/app/administration/test/e2e/node_modules/@shopware-ag/e2e-testsuite-platform/routes/cypress.js;

# Start Cypress in CLI
printf "### Starting Cypress\n\n"
docker exec -w /e2e-"$CYPRESS_ENV" "$CONTAINER" cypress run --config baseUrl="$APP_URL" $CYPRESS_PARAMS
