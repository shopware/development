#!/usr/bin/env bash

mysqldump -u '__DB_USER__' -p'__DB_PASSWORD__' -h '__DB_HOST__' --port='__DB_PORT__' '__DB_NAME__' > '__E2E_RESTORE_DUMP_PATH__'
