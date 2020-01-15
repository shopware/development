#!/usr/bin/env sh

docker exec -u __USERKEY__ __APP_ID__ ./psh.phar e2e:dump-db;
docker exec -u __USERKEY__ __APP_ID__ forever start vendor/shopware/platform/src/Administration/Resources/app/administration/test/e2e/node_modules/@shopware-ag/e2e-testsuite-platform/routes/cypress.js;
