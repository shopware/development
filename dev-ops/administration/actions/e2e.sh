#!/usr/bin/env sh
#DESCRIPTION: runs e2e tests with nightwatch for the administration

if [ "__E2E_ENV__" = "default" ]; then DATABASE_URL="mysql://__DB_USER__:__DB_PASSWORD__@127.0.0.1:4406/__DB_NAME__" bin/console administration:dump:features; else bin/console administration:dump:features; fi

if [ "__E2E_ENV__" = "default" ]; then mysqldump -u __DB_USER__ -p__DB_PASSWORD__ -h 127.0.0.1 --port=4406 __DB_NAME__ > vendor/shopware/platform/src/Administration/Resources/e2e/temp/clean_db.sql; else mysqldump -u __DB_USER__ -p__DB_PASSWORD__ -h __DB_HOST__ --port=__DB_PORT__ __DB_NAME__ > vendor/shopware/platform/src/Administration/Resources/e2e/temp/clean_db.sql; fi

E2E_ENV=__E2E_ENV__ DB_USER=__DB_USER__ DB_PASSWORD=__DB_PASSWORD__ DB_NAME=__DB_NAME__ PROJECT_ROOT=__ROOT__/ NIGHTWATCH_HEADLESS=__NIGHTWATCH_HEADLESS__ APP_URL=__APP_URL__ NIGHTWATCH_ENV=__NIGHTWATCH_ENV__ vendor/shopware/platform/src/Administration/Resources/e2e/node_modules/nightwatch/bin/nightwatch --config vendor/shopware/platform/src/Administration/Resources/e2e/runner-config.js --reporter junit --env __E2E_ENV__ __NIGHTWATCH_PARAMS__