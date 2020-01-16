#!/usr/bin/env sh

# Constants
CYPRESS_ENV=$1
APP_URL=$2
CYPRESS_PARAMS=$3
ROOT="./platform/src/$CYPRESS_ENV/Resources/app/${CYPRESS_ENV,,}/test/e2e"

printf "\nCypress environment: ${1}\n"
printf "App-URL: ${2}\n"
printf "Path: ${ROOT}\n\n"

# Prepare Shopware
bin/console theme:change --all Storefront
bin/console cache:clear
./psh.phar e2e:dump-db;

# Start Cypress in CLI
cd "./platform/src/$CYPRESS_ENV/Resources/app/${CYPRESS_ENV,,}/test/e2e" || exit
CYPRESS_baseUrl="${APP_URL}" CYPRESS_localUsage=true CYPRESS_projectRoot="${ROOT}" ./node_modules/.bin/cypress run $CYPRESS_PARAMS
