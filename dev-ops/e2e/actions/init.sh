#!/usr/bin/env bash
#DESCRIPTION: installs the dependencies for the e2e tests using npm in cypress container

#./psh.phar e2e:start
if [[ -z "__CYPRESS_ID__" ]]; then docker exec -u __USERKEY__ __CYPRESS_ID__ npm clean-install --prefix ./e2e; else npm clean-install --prefix vendor/shopware/platform/src/Administration/Resources/e2e/; fi
