#!/usr/bin/env bash

mysql -u '__DB_USER__' -p'__DB_PASSWORD__' -h '__DB_HOST__' --port='__DB_PORT__' -e "DROP DATABASE IF EXISTS \`__DB_NAME__\`"
mysql -u '__DB_USER__' -p'__DB_PASSWORD__' -h '__DB_HOST__' --port='__DB_PORT__' -e "CREATE DATABASE \`__DB_NAME__\` DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci"
mysql -u '__DB_USER__' -p'__DB_PASSWORD__' -h '__DB_HOST__' --port='__DB_PORT__' __DB_NAME__ < vendor/shopware/platform/src/Core/schema.sql