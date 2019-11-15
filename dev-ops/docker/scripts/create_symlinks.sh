#!/usr/bin/env bash

APP_CONTAINER=$(docker-compose ps -q app_server)

docker exec -i ${APP_CONTAINER} /bin/sh -c 'ln -s /app/platform/src/Administration/Resources/e2e /e2e-Administration'
docker exec -i ${APP_CONTAINER} /bin/sh -c 'ln -s /app/platform/src/Storefront/Resources/e2e /e2e-Storefront'
