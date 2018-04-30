#!/usr/bin/env bash

docker exec -u __USERKEY__ __APP_ID__ ./psh.phar bamboo:init-composer --PLATFORM_BRANCH="__PLATFORM_BRANCH__"
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar administration:init
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar administration:generate-api-docs