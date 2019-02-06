#!/usr/bin/env bash
#DESCRIPTION: runs & auto watch files to re-run unit tests automatically on change

PROJECT_ROOT=__PROJECT_ROOT__ APP_URL=__APP_URL__ CHROME_BIN=__CHROME_BIN__ BABEL_ENV=test TESTING_ENV=watch ENV_FILE=__PROJECT_ROOT__/.env npm run --prefix vendor/shopware/platform/src/Administration/Resources/administration/ unit-watch