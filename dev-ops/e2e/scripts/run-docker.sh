#!/usr/bin/env bash

# Constants
CONTAINER=$1
CYPRESS_ENV=$2
CYPRESS_PARAMS=$4

export CYPRESS_baseUrl=$3
export CYPRESS_localUsage=false

printf "\nCypress environment: ${CYPRESS_ENV}\n"
printf "App-URL: ${CYPRESS_baseUrl}\n\n"

# Start Cypress in CLI
printf "### Starting Cypress\n\n"
docker exec \
    -e CYPRESS_baseUrl -e CYPRESS_localUsage \
    -w /e2e-"$CYPRESS_ENV" "$CONTAINER" \
    cypress run $CYPRESS_PARAMS
