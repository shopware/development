#!/usr/bin/env bash

INCLUDE: ./../../administration/actions/init.sh
INCLUDE: ./../../administration/actions/build.sh

APP_URL=__APP_URL__ vendor/shopware/platform/src/Administration/Resources/administration/node_modules/nightwatch/bin/nightwatch --config vendor/shopware/platform/src/Administration/Resources/administration/test/e2e/nightwatch.ci.conf.js --reporter junit
