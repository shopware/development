#!/usr/bin/env sh
#DESCRIPTION: runs e2e tests with nightwatch for the administration

if [ "__E2E_ENV__" = "default" ]; then DATABASE_URL="mysql://__DB_USER__:__DB_PASSWORD__@127.0.0.1:4406/__DB_NAME__" bin/console administration:dump:features; else bin/console administration:dump:features; fi

INCLUDE: ./dump-db.sh

E2E_ENV=__E2E_ENV__ PROJECT_ROOT=__ROOT__/  NIGHTWATCH_HEADLESS=__NIGHTWATCH_HEADLESS__ APP_URL=__APP_URL__ NIGHTWATCH_ENV=__NIGHTWATCH_ENV__ vendor/shopware/platform/src/Administration/Resources/e2e/node_modules/nightwatch/bin/nightwatch --config vendor/shopware/platform/src/Administration/Resources/e2e/runner-config.js --reporter junit --env __E2E_ENV__ __NIGHTWATCH_PARAMS__