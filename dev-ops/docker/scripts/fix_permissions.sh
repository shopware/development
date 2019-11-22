#!/usr/bin/env bash

APP_CONTAINER=$(docker-compose ps -q app_server)

docker exec -i ${APP_CONTAINER} /bin/sh -c 'chown -Rf application:application /app /.npm /.composer'
docker exec -i ${APP_CONTAINER} /bin/sh -c 'chmod -R 0777 /app /.npm /.composer'
