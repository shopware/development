#!/usr/bin/env bash

docker-compose build && docker-compose up -d
wait

dev-ops/bamboo.shopware.com/bootstrap.sh __APP_ID__ __USER_ID__