#!/usr/bin/env bash
#DESCRIPTION: runs & auto watch files to re-run unit tests automatically on change

INCLUDE: ./dump-entity-schema.sh
TTY: PROJECT_ROOT=__PROJECT_ROOT__
     ADMIN_PATH=__PROJECT_ROOT__/vendor/shopware/platform/src/Administration/Resources/app/administration
     APP_URL=__APP_URL__
     CHROME_BIN=__CHROME_BIN__
     BABEL_ENV=test
     TESTING_ENV=watch
     ENV_FILE=__PROJECT_ROOT__/.env
     npm run --prefix vendor/shopware/platform/src/Administration/Resources/app/administration/ unit-watch
