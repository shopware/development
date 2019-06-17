#!/usr/bin/env sh

#DESCRIPTION: opens e2e tests runner

INCLUDE: ./../../common/actions/cache.sh

bin/console administration:dump:features

if [[ -z "__CYPRESS_LOCAL__" ]]; then ./psh.phar e2e:prepare-container; else ./psh.phar e2e:dump-db; fi

docker exec -u __USERKEY__ __CYPRESS_ID__ npm run --prefix app/vendor/shopware/platform/src/Administration/Resources lerna -- bootstrap;
vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/e2e/node_modules/.bin/cypress open --project ./vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/e2e --config baseUrl=__APP_URL__ --env localUsage=__CYPRESS_LOCAL__,projectRoot=__PROJECT_ROOT__
