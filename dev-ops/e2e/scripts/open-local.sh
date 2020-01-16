#!/usr/bin/env bash

# Constants
CYPRESS_ENV=$1
APP_URL=$2
ROOT=$3

printf "\nCypress environment: ${CYPRESS_ENV}\n"
printf "App-URL: ${APP_URL}\n"

# Prepare Shopware
bin/console theme:change --all Storefront
bin/console cache:clear
./psh.phar e2e:dump-db;

# Start Cypress test runner
cd "./platform/src/$CYPRESS_ENV/Resources/app/${CYPRESS_ENV,,}/test/e2e" || exit
CYPRESS_baseUrl="${APP_URL}" CYPRESS_localUsage=true CYPRESS_projectRoot="${ROOT}" npm run open "${CYPRESS_PARAMS}"
