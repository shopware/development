#!/usr/bin/env bash

# Constants
CONTAINER=$1
CYPRESS_ENV=$2

docker exec "$CONTAINER" npm clean-install --prefix ./e2e-"$CYPRESS_ENV";
