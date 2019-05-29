#!/usr/bin/env sh

#DESCRIPTION: opens e2e tests runner

INCLUDE: ./../../common/actions/cache.sh

bin/console administration:dump:features

if [[ -z "__APP_ID__" ]]; then ./psh.phar e2e:dump-db; else docker exec -u __USERKEY__ __APP_ID__ ./psh.phar e2e:dump-db;  fi
if [[ ! -z "__APP_ID__" ]]; then docker exec -u __USERKEY__ __APP_ID__ forever start platform/src/__CYPRESS_ENV__/Resources/e2e/routes/cypress.js; fi

npm clean-install --prefix vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/e2e/

vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/e2e/node_modules/.bin/cypress open --project ./vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/e2e --config baseUrl=__APP_URL__
