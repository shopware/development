#!/usr/bin/env bash

# Stop docker-sync
if [ -n "__DOCKER_SYNC_ENABLED__" ]; then docker-sync stop; fi

docker-compose stop
