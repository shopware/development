#!/usr/bin/env sh

#DESCRIPTION: runs e2e tests with nightwatch for the administration

INCLUDE: ./../../common/actions/cache.sh

bin/console administration:dump:features

INCLUDE: ./dump-db.sh

APP_WATCH=__APP_WATCH__ DEVPORT=__DEVPORT__ E2E_ENV=__E2E_ENV__ PROJECT_ROOT=__PROJECT_ROOT__/  NIGHTWATCH_HEADLESS=__NIGHTWATCH_HEADLESS__ APP_URL=__APP_URL__ NIGHTWATCH_ENV=__NIGHTWATCH_ENV__ DB_NAME=__DB_NAME__ vendor/shopware/platform/src/Administration/Resources/e2e/node_modules/nightwatch/bin/nightwatch --config vendor/shopware/platform/src/Administration/Resources/e2e/runner-config.js --reporter junit --env __E2E_ENV__ __NIGHTWATCH_PARAMS__
