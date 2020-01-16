#!/usr/bin/env bash

docker exec -u "$1" "$2" bin/console theme:change --all Storefront
docker exec -u "$1" "$2" bin/console cache:clear

docker exec -u "$1" "$2" ./psh.phar e2e:dump-db;
docker exec -u "$1" "$2" forever start vendor/shopware/platform/src/Administration/Resources/app/administration/test/e2e/node_modules/@shopware-ag/e2e-testsuite-platform/routes/cypress.js;
