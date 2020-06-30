#!/usr/bin/env bash

# Constants
USER_KEY=$1
CONTAINER=$2

# Prepare Shopware
docker exec -u "$USER_KEY" "$CONTAINER" ./psh.phar e2e:prepare-shopware

# Prepare database dump
docker exec -u "$USER_KEY" "$CONTAINER" ./psh.phar e2e:prepare-environment;
