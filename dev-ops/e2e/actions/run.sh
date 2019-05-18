#!/usr/bin/env sh

# INCLUDE: ./../scripts/prepare-app-server-for-e2e.sh

if [[ -z "__CYPRESS_ID__" ]]; then docker exec -u __USERKEY__ __CYPRESS_ID__ npx cypress run --browser chrome --project /e2e-__CYPRESS_ENV__ --config baseUrl=__APP_URL__ __CYPRESS_PARAMS__ || echo Failures: $?; else npx cypress run --browser chrome --project vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/e2e --config baseUrl=__APP_URL__ __CYPRESS_PARAMS__; fi
