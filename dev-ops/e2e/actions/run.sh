#!/usr/bin/env sh

# INCLUDE: ./../scripts/prepare-app-server-for-e2e.sh

docker exec -u __USERKEY__ __CYPRESS_ID__ npx cypress run --browser chrome --spec /e2e/cypress/integration/__CYPRESS_ENV__/**/* --project /e2e --config baseUrl=__APP_URL__ || echo Failures: $?
