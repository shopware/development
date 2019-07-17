#!/usr/bin/env bash

echo "SET unique_checks=0;SET foreign_key_checks=0;" | mysqldump -u '__DB_USER__' -p'__DB_PASSWORD__' -h '__DB_HOST__' --port='__DB_PORT__' -q --opt '__DB_NAME__' --no-autocommit | gzip -9 > __E2E_RESTORE_DUMP_PATH__
