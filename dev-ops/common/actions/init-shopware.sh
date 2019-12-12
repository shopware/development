#!/usr/bin/env bash
#DESCRIPTION: initialization of shopware

INCLUDE: ./cache.sh

bin/console database:migrate --all core
bin/console database:migrate-destructive --all core

bin/console dal:refresh:index

bin/console scheduled-task:register

bin/console plugin:refresh

bin/console user:create admin --password=shopware --admin

bin/console sales-channel:create:storefront --url='__APP_URL__'

bin/console theme:refresh
