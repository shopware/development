#!/usr/bin/env bash

# Constants
CONTAINER=$1
export CYPRESS_baseUrl=$2
export CYPRESS_shopwareRoot="/app"
export CYPRESS_localUsage=false

printf "App-URL: ${CYPRESS_baseUrl}\n\n"

# Start Cypress in test runner
printf "### Starting Cypress\n\n"
docker exec \
    -e CYPRESS_baseUrl -e CYPRESS_localUsage -e CYPRESS_shopwareRoot \
    -w /e2e-"$CYPRESS_ENV" "$CONTAINER" \
    cypress open --project /e2e-"$CYPRESS_ENV"
