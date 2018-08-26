#!/usr/bin/env bash
#DESCRIPTION: runs e2e tests with nightwatch for the administration

APP_URL=__APP_URL__ vendor/shopware/platform/src/Administration/Resources/administration/node_modules/nightwatch/bin/nightwatch --config vendor/shopware/platform/src/Administration/Resources/administration/test/e2e/nightwatch.conf.js --reporter junit