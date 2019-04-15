#!/usr/bin/env bash
#DESCRIPTION: initialization of shopware

INCLUDE: ./cache.sh

bin/console database:migrate --all Shopware\\
bin/console database:migrate-destructive --all Shopware\\

bin/console scheduled-task:register

bin/console plugin:refresh

bin/console user:create admin --password=shopware

php bin/console sales-channel:create:storefront --url='__APP_URL__'
