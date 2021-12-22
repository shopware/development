#!/usr/bin/env bash

# Constants
CONTAINER=$1
export CYPRESS_baseUrl=$2
CYPRESS_PARAMS=$3

export CYPRESS_localUsage=false

printf "App-URL: ${CYPRESS_baseUrl}\n\n"

# Start Cypress in CLI
printf "### Starting Cypress\n\n"
docker exec \
    -e CYPRESS_baseUrl -e CYPRESS_localUsage \
    -w /e2e "$CONTAINER" \
    cypress run $CYPRESS_PARAMS
