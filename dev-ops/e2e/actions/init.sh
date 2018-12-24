#!/usr/bin/env bash
#DESCRIPTION: builds the administration and installs the dependencies for the e2e tests using npm

INCLUDE: ./../../administration/actions/init.sh
INCLUDE: ./../../administration/actions/build.sh

npm install --prefix vendor/shopware/platform/src/Administration/Resources/e2e/