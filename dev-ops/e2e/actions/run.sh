#!/usr/bin/env bash
#DESCRIPTION: Runs Cypress' e2e tests in CLI

if [ "__CYPRESS_LOCAL__" = "1" ]; then bash ./dev-ops/e2e/scripts/run-local.sh __APP_URL__ __PROJECT_ROOT__ "__CYPRESS_PARAMS__"; fi
if [ "__CYPRESS_LOCAL__" != "1" ]; then bash ./dev-ops/e2e/scripts/run-docker.sh __CYPRESS_ID__ __APP_URL__ "__CYPRESS_PARAMS__"; fi
