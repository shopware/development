#!/usr/bin/env bash

# Constants
export CYPRESS_baseUrl=$1
export CYPRESS_shopwareRoot="${2:-"$PWD"}"
export CYPRESS_localUsage=true
export APP_ENV=e2e

printf "App-URL: ${CYPRESS_baseUrl}\n"

cd "./platform/tests/e2e"

# Start Cypress test runner
npm run open "${CYPRESS_PARAMS}"
