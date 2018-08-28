#!/usr/bin/env bash
#DESCRIPTION: runs e2e tests with nightwatch for the administration

NIGHTWATCH_HEADLESS=__NIGHTWATCH_HEADLESS__ APP_URL=__APP_URL__ vendor/shopware/platform/src/Administration/Resources/administration/node_modules/nightwatch/bin/nightwatch --config vendor/shopware/platform/src/Administration/Resources/administration/test/e2e/nightwatch.conf.js --reporter junit --env __NIGHTWATCH_ENV__