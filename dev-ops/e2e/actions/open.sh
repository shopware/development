#!/usr/bin/env bash
#DESCRIPTION: Opens Cypress' e2e tests runner

if [ "__CYPRESS_LOCAL__" = "1" ]; then bash ./dev-ops/e2e/scripts/open-local.sh __APP_URL__ __PROJECT_ROOT__;  fi
if [ "__CYPRESS_LOCAL__" != "1" ]; then bash ./dev-ops/e2e/scripts/open-docker.sh __CYPRESS_ID__ __APP_URL__;  fi
