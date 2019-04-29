#!/usr/bin/env bash

docker exec -u __USERKEY__ __APP_ID__ ./psh.phar bamboo:init-composer
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar administration:install-dependencies
docker exec -u __USERKEY__ __APP_ID__ ./psh.phar administration:storybook-generate
