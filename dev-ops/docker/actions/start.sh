#!/usr/bin/env bash


# check that the ~/.npm and ~/.composer folder are not owned by the root user
dev-ops/docker/scripts/check_permissions.sh

docker-compose build && docker-compose up -d
wait

docker exec __APP_ID__ /addExternalUser __USER_ID__