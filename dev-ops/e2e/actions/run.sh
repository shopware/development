#!/usr/bin/env sh

if [[ -z "__APP_ID__" ]]; then ./psh.phar e2e:dump-db; else docker exec -u __USERKEY__ __APP_ID__ ./psh.phar e2e:dump-db; fi
if [[ -z "__APP_ID__" ]]; then forever start platform/src/Administration/Resources/e2e/routes/cypress.js; else docker exec -u __USERKEY__ __APP_ID__ forever start platform/src/Administration/Resources/e2e/routes/cypress.js; fi
if [[ -z "__CYPRESS_ID__" ]]; then npx cypress run --browser chrome --project vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/e2e --config baseUrl=__APP_URL__ __CYPRESS_PARAMS__ || echo Failures: $?; else docker exec -u __USERKEY__ __CYPRESS_ID__ npx cypress run --env container=__APP_ID__ --browser chrome --project /e2e-__CYPRESS_ENV__ --config baseUrl=__APP_URL__ __CYPRESS_PARAMS__ || echo Failures: $?; fi
