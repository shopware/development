#!/usr/bin/env bash
#DESCRIPTION: runs unit tests with jest for the administration

INCLUDE: ./dump-entity-schema.sh
PROJECT_ROOT=__PROJECT_ROOT__ ADMIN_PATH=__PROJECT_ROOT__/vendor/shopware/platform/src/Administration/Resources/app/administration APP_URL=__APP_URL__ CHROME_BIN=__CHROME_BIN__ BABEL_ENV=test TESTING_ENV=single ENV_FILE=__PROJECT_ROOT__/.env npm run --prefix vendor/shopware/platform/src/Administration/Resources/app/administration/ unit
