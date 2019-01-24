#!/usr/bin/env bash

mysql -u __DB_USER__ -p__DB_PASSWORD__ -h __DB_HOST__ --port=__DB_PORT__ -e "DROP DATABASE IF EXISTS \`__DB_NAME___test\`"
mysql -u __DB_USER__ -p__DB_PASSWORD__ -h __DB_HOST__ --port=__DB_PORT__ -e "CREATE DATABASE \`__DB_NAME___test\` DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci"

mysqldump __DB_NAME__ -u __DB_USER__ -p__DB_PASSWORD__ -h __DB_HOST__ --port=__DB_PORT__ | mysql __DB_NAME___test -u __DB_USER__ -p__DB_PASSWORD__ -h __DB_HOST__ --port=__DB_PORT__
