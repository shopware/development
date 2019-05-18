#!/usr/bin/env sh

#DESCRIPTION: opens e2e tests runner

INCLUDE: ./../../common/actions/cache.sh

bin/console administration:dump:features

npm clean-install --prefix vendor/shopware/platform/src/Administration/Resources/e2e/
vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/e2e/node_modules/.bin/cypress open --project ./vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/e2e --config baseUrl=__APP_URL__
