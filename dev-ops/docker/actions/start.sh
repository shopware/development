#!/usr/bin/env bash


# check that the ~/.npm and ~/.composer folder are not owned by the root user
dev-ops/docker/scripts/check_permissions.sh

docker-compose build && docker-compose up -d
wait

dev-ops/docker/bootstrap.sh __APP_ID__ __USER_ID__ __SSH_PRIVATE_KEY_PATH__