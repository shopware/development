#!/usr/bin/env bash

# check that the ~/.npm and ~/.composer folder are not owned by the root user
dev-ops/docker/scripts/check_permissions.sh

# Start docker-sync
if [ -n "__DOCKER_SYNC_ENABLED__" ]; then docker-sync start && echo "\n docker-sync is initially indexing files. It may take some minutes, until code changes take effect"; fi

docker-compose build --parallel && docker-compose up -d
