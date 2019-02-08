#!/usr/bin/env bash

./psh.phar bamboo:start

# prepare platform
I: mkdir ./build/artifacts
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar init-composer
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar administration:init

# init artifact dependencies
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar analyze:init-sync

# do analysis
I: docker exec -u __USERKEY__ __APP_ID__ ./psh.phar analyze:static

