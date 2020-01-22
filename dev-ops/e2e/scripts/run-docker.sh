#!/usr/bin/env bash

# Constants
CONTAINER=$1
CYPRESS_ENV=$2
APP_URL=$3
CYPRESS_PARAMS=$4

printf "\nCypress environment: ${CYPRESS_ENV}\n"
printf "App-URL: ${APP_URL}\n\n"

# Start Cypress in CLI
printf "### Starting Cypress\n\n"
docker exec -w /e2e-"$CYPRESS_ENV" "$CONTAINER" cypress run --config baseUrl="$APP_URL" $CYPRESS_PARAMS
