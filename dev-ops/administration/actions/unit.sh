#!/usr/bin/env bash
#DESCRIPTION: runs unit tests with karma for the administration

PROJECT_ROOT=__PROJECT_ROOT__ APP_URL=__APP_URL__ CHROME_BIN=__CHROME_BIN__ BABEL_ENV=test TESTING_ENV=single ENV_FILE=__PROJECT_ROOT__/.env npm run --prefix vendor/shopware/platform/src/Administration/Resources/administration/ unit