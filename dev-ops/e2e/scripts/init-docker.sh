#!/usr/bin/env bash

# Constants
USER_KEY=$1
CONTAINER=$2

# Prepare Shopware
docker exec -u "$USER_KEY" "$CONTAINER" ./psh.phar e2e:prepare-shopware

# Prepare database dump
docker exec -u "$USER_KEY" "$CONTAINER" ./psh.phar e2e:prepare-environment;

# Start express server for docker usage
docker exec -u "$USER_KEY" "$CONTAINER" forever start vendor/shopware/platform/src/Administration/Resources/app/administration/test/e2e/node_modules/@shopware-ag/e2e-testsuite-platform/routes/cypress.js;
