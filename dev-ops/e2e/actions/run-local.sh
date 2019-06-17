#!/usr/bin/env sh

./psh.phar e2e:dump-db;
npx cypress run --project vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/e2e --config baseUrl=__APP_URL__ __CYPRESS_PARAMS__ || echo Failures: $?
