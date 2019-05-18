#!/usr/bin/env sh

# INCLUDE: ./../scripts/prepare-app-server-for-e2e.sh

if [[ -z "__CYPRESS_ID__" ]]; then docker exec -u __USERKEY__ __CYPRESS_ID__ npx cypress run --browser chrome --spec /e2e/cypress/integration/__CYPRESS_ENV__/**/* --project /e2e --config baseUrl=__APP_URL__ __CYPRESS_PARAMS__ || echo Failures: $?; else npx cypress run --browser chrome --project vendor/shopware/platform/src/Administration/Resources/cypress --config baseUrl=__APP_URL__ __CYPRESS_PARAMS__; fi
