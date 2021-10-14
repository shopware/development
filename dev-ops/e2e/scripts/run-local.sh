#!/usr/bin/env sh

# Constants
CYPRESS_ENV=$1
export CYPRESS_baseUrl=$2
export CYPRESS_shopwareRoot="${3:-"$PWD"}"
CYPRESS_PARAMS=$4
export APP_ENV=e2e
export CYPRESS_localUsage=true

printf "\nCypress environment: ${1}\n"
printf "App-URL: ${CYPRESS_baseUrl}\n"

# Prepare Shopware
bin/console theme:change --all Storefront
bin/console cache:clear
bin/console e2e:dump-db

# Start Cypress in CLI
if [ $CYPRESS_ENV == "Recovery" ]; then
    cd "./platform/src/Recovery/Test/e2e"
else
    cd "./platform/src/$CYPRESS_ENV/Resources/app/$(echo $CYPRESS_ENV | tr '[:upper:]' '[:lower:]')/test/e2e" || exit
fi

./node_modules/.bin/cypress run $CYPRESS_PARAMS
