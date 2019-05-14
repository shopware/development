#!/usr/bin/env bash

docker exec -u __USERKEY__ __APP_ID__ /usr/local/bin/wait-for-it.sh --timeout=120 mysql:3306
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar bamboo:init
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar bamboo:e2e-storefront --CYPRESS_ENV="storefront" --DB_NAME="shopware_e2e" --APP_ENV="prod"
docker exec -u __USERKEY__ __CYPRESS_ID__ npm clean-install --prefix /e2e
docker exec -u __USERKEY__ __CYPRESS_ID__ npx cypress run --project /e2e --browser chrome --spec /e2e/cypress/integration/storefront/**/* --config baseUrl=__APP_URL__ || echo Exit code $?

