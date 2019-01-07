#!/usr/bin/env bash

if [ "__E2E_ENV__" = "default" ]; then (echo 'SET SESSION SQL_LOG_BIN=0;'; cat /tmp/e2e_dump.sql) | mysql -u __DB_USER__ -p__DB_PASSWORD__ -h 127.0.0.1 --port=4406 __DB_NAME__ < /tmp/e2e_dump.sql; else (echo 'SET SESSION SQL_LOG_BIN=0;'; cat /tmp/e2e_dump.sql) | mysql -u __DB_USER__ -p__DB_PASSWORD__ -h app_mysql --port=3306 __DB_NAME__; fi
