#!/usr/bin/env bash

# Prepare app container
./psh.phar e2e:prepare-container;

# Start Cypress test runner
URL=__APP_URL__ docker-compose -f docker-compose.yml -f ./docker-compose.override.yml up --no-deps cypress
