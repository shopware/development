#!/usr/bin/env bash

mysql -u '__DB_USER__' -p__DB_PASSWORD__ -h '__DB_HOST__' -e 'SET autocommit=0;' '__DB_NAME__'
mysql -u '__DB_USER__' -p__DB_PASSWORD__ -h '__DB_HOST__' -e 'SET unique_checks=0;' '__DB_NAME__'
mysql -u '__DB_USER__' -p__DB_PASSWORD__ -h '__DB_HOST__' -e 'SET foreign_key_checks=0;' '__DB_NAME__'
mysqldump -u '__DB_USER__' -p'__DB_PASSWORD__' -h '__DB_HOST__' --port='__DB_PORT__' -q --opt '__DB_NAME__' | gzip -9 > __E2E_RESTORE_DUMP_PATH__
mysql -u '__DB_USER__' -p__DB_PASSWORD__ -h '__DB_HOST__' -e 'COMMIT;' '__DB_NAME__'
