#!/usr/bin/env bash

docker-compose build && docker-compose up -d
wait

dev-ops/docker/bootstrap.sh __APP_ID__ __USER_ID__ __SSH_PRIVATE_KEY_PATH__