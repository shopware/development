#!/usr/bin/env bash
#DESCRIPTION: initialization of shopware

php bin/console cache:clear --env=prod
php bin/console cache:clear

bin/console database:migrate --all
bin/console database:migrate-destructive --all

bin/console plugin:update

bin/console rest:user:create admin --password=shopware

php bin/console sales-channel:create --id=20080911ffff4fffafffffff19830531
php bin/console sales-channel:create:storefront --url=__APP_URL__