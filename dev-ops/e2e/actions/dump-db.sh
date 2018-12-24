#!/usr/bin/env bash

if [ "__E2E_ENV__" = "default" ]; then mysqldump -u __DB_USER__ -p__DB_PASSWORD__ -h 127.0.0.1 --port=4406 __DB_NAME__ > /tmp/e2e_dump.sql; else mysqldump -u __DB_USER__ -p__DB_PASSWORD__ -h __DB_HOST__ --port=__DB_PORT__ __DB_NAME__ > /tmp/e2e_dump.sql; fi
