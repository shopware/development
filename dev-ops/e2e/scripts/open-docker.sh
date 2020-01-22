#!/usr/bin/env bash

# Constants
CONTAINER=$1
CYPRESS_ENV=$2
APP_URL=$3

printf "\nCypress environment: ${CYPRESS_ENV}\n"
printf "App-URL: ${APP_URL}\n\n"

# Start Cypress test runner
printf "### Starting Cypress\n\n"
docker exec -w /e2e-"$CYPRESS_ENV" "$CONTAINER" cypress open --project /e2e-"$CYPRESS_ENV" --config baseUrl="$APP_URL"
