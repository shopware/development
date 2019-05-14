#!/usr/bin/env sh

#DESCRIPTION: opens e2e tests runner

INCLUDE: ./../../common/actions/cache.sh

bin/console administration:dump:features

INCLUDE: ./dump-db.sh

vendor/shopware/platform/src/Administration/Resources/e2e/node_modules/.bin/cypress open --project ./vendor/shopware/platform/src/Administration/Resources/e2e
