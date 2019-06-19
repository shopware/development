#!/usr/bin/env bash
#DESCRIPTION: installs the dependencies for the e2e tests using npm

npm clean-install --prefix vendor/shopware/platform/src/Administration/Resources/
CHROMEDRIVER_VERSION=LATEST CHROMEDRIVER_FORCE_DOWNLOAD=true npm run --prefix vendor/shopware/platform/src/Administration/Resources/ lerna bootstrap
CHROMEDRIVER_VERSION=LATEST CHROMEDRIVER_FORCE_DOWNLOAD=true npm install --prefix vendor/shopware/platform/src/Administration/Resources/e2e chromedriver
