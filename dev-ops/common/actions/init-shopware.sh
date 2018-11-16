#!/usr/bin/env bash
#DESCRIPTION: initialization of shopware

bin/console database:migrate --all
bin/console database:migrate-destructive --all

bin/console plugin:update

bin/console rest:user:create admin --password=shopware

php bin/console sales-channel:create --id=20080911ffff4fffafffffff19830531
php bin/console sales-channel:create:storefront --url=__APP_URL__

APP_ENV=prod php bin/console cache:clear
php bin/console cache:clear
