#!/usr/bin/env bash

I: cp dev-ops/bamboo.shopware.com/docker-compose.override.yml .

docker-compose build && docker-compose up -d
wait

dev-ops/bamboo.shopware.com/bootstrap.sh __APP_ID__ __USER_ID__ __SSH_PRIVATE_KEY_PATH__