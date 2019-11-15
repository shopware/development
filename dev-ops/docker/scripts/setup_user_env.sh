#!/usr/bin/env bash

APP_CONTAINER=$(docker-compose ps -q app_server)

docker exec -i ${APP_CONTAINER} /bin/sh -c 'ln -s /app/bin/console /usr/bin/console'
docker exec -i ${APP_CONTAINER} /bin/sh -c "echo \"alias ll='ls -la --color=auto'\" >> /home/application/.bashrc"
