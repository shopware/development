#!/usr/bin/env sh

./psh.phar e2e:dump-db;
npx cypress run --project vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/app/__CYPRESS_ENV__/test/e2e --config baseUrl=__APP_URL__ __CYPRESS_PARAMS__ --env localUsage=__CYPRESS_LOCAL__,projectRoot=__PROJECT_ROOT__ || echo Failures: $?
