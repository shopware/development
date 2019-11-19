#!/usr/bin/env sh

INCLUDE: ./prepare-container.sh
docker exec -u __USERKEY__ __CYPRESS_ID__ npx cypress run --env container=__APP_ID__ --browser chrome --project /e2e-__CYPRESS_ENV__ --config baseUrl=__APP_URL__ __CYPRESS_PARAMS__
