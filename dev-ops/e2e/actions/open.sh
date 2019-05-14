#!/usr/bin/env sh

#DESCRIPTION: opens e2e tests runner

INCLUDE: ./../../common/actions/cache.sh

bin/console administration:dump:features

npm clean-install --prefix vendor/shopware/platform/src/Administration/Resources/cypress/
vendor/shopware/platform/src/Administration/Resources/cypress/node_modules/.bin/cypress open --project ./vendor/shopware/platform/src/Administration/Resources/cypress
