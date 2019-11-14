#!/usr/bin/env bash

./psh.phar e2e:dump-db;
vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/app/__CYPRESS_ENV__/test/e2e/node_modules/.bin/cypress open --project ./vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/app/__CYPRESS_ENV__/test/e2e --config baseUrl=__APP_URL__ --env localUsage=__CYPRESS_LOCAL__,projectRoot=__PROJECT_ROOT__;
