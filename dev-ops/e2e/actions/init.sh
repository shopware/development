#!/usr/bin/env sh
#DESCRIPTION: Installs the dependencies for the e2e tests using npm in cypress container

if [[ -z "__CYPRESS_LOCAL__" ]]; then bash ./dev-ops/e2e/scripts/init-docker.sh __CYPRESS_ID__ __CYPRESS_ENV__; else bash ./dev-ops/e2e/scripts/init-local.sh __CYPRESS_ENV__; fi
