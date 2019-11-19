#!/usr/bin/env bash
#DESCRIPTION: installs the dependencies for the e2e tests using npm in cypress container

npm clean-install --prefix vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/app/__CYPRESS_FOLDER__/test/e2e/;
