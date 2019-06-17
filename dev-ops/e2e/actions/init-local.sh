#!/usr/bin/env bash
#DESCRIPTION: installs the dependencies for the e2e tests using npm in cypress container

npm run --prefix vendor/shopware/platform/src/Administration/Resources lerna -- bootstrap;
