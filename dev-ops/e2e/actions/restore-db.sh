#!/usr/bin/env bash
#DESCRIPTION: Restores shopware backup

(echo 'SET SESSION SQL_LOG_BIN=0;'; zcat < '__E2E_RESTORE_DUMP_PATH__') | mysql -u '__DB_USER__' -p__DB_PASSWORD__ -h '__DB_HOST__' --port='__DB_PORT__' '__DB_NAME__'
