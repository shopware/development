#!/usr/bin/env bash

docker exec -u __USERKEY__ __APP_ID__ /usr/local/bin/wait-for-it.sh --timeout=120 mysql:3306
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar bamboo:init
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar bamboo:e2e-storefront --CYPRESS_ENV="storefront" --DB_NAME="shopware_e2e" --APP_ENV="prod"
docker exec -u __USERKEY__ __CYPRESS_ID__ npm clean-install --prefix ./e2e-Storefront
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar e2e:dump-db
docker exec -u __USERKEY__ __APP_ID__ forever start vendor/shopware/platform/src/Storefront/Resources/e2e/routes/cypress.js
docker exec -u __USERKEY__ __CYPRESS_ID__ npx cypress run --project /e2e-Storefront --browser chrome --config baseUrl=__APP_URL__ || echo Failures: $?

