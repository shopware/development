#!/usr/bin/env bash
#DESCRIPTION: installs the dependencies for the e2e tests using npm

npm clean-install --prefix vendor/shopware/platform/src/Administration/Resources/e2e/
