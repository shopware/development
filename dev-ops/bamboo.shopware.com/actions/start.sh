#!/usr/bin/env bash

docker-compose build && docker-compose up -d
wait

docker exec __APP_ID__ /addExternalUser __USER_ID__
