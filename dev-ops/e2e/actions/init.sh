#!/usr/bin/env bash
#DESCRIPTION: installs the dependencies for the e2e tests using npm in cypress container

if [[ -z "__CYPRESS_ID__" ]]; then npm clean-install --prefix vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/e2e/; else docker exec -u __USERKEY__ __CYPRESS_ID__ npm clean-install --prefix ./e2e-__CYPRESS_ENV__; fi
if [[ ! -z "__APP_ID__" ]]; then docker exec __APP_ID__ npm install -g forever; fi
