#!/usr/bin/env sh

docker exec -u __USERKEY__ __APP_ID__ ./psh.phar e2e:dump-db;
docker exec -u __USERKEY__ __APP_ID__ forever start platform/src/Administration/Resources/app/administration/test/e2e/routes/cypress.js;
