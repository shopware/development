#!/usr/bin/env bash

docker cp . "$(docker ps -f name=data --format '{{.Names}}')":/app

dev-ops/docker/scripts/fix_permissions.sh
