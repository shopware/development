#!/usr/bin/env sh

INCLUDE: ./prepare-container.sh
docker exec -u __USERKEY__ __CYPRESS_ID__ cypress run --config baseUrl=__APP_URL__ __CYPRESS_PARAMS__
