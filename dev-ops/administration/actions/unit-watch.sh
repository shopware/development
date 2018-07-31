#!/usr/bin/env bash
#DESCRIPTION: runs & auto watch files to re-run unit tests automatically on change
APP_URL=__APP_URL__ BABEL_ENV=test TESTING_ENV=watch npm run --prefix vendor/shopware/platform/src/Administration/Resources/administration/ unit-watch