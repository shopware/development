#!/usr/bin/env bash
#DESCRIPTION: Creates backup of Shopware's database

bin/console theme:change --all Storefront

bin/console e2e:dump-db --env=e2e || true

echo "SET unique_checks=0;SET foreign_key_checks=0;" | mysqldump -u '__DB_USER__' -p'__DB_PASSWORD__' -h '__DB_HOST__' --port='__DB_PORT__' -q --opt '__DB_NAME__' --no-autocommit --ignore-table '__DB_NAME__.enqueue' --ignore-table '__DB_NAME__.message_queue_stats' | gzip -9 > __E2E_RESTORE_DUMP_PATH__
