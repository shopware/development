#!/usr/bin/env bash
#DESCRIPTION: initialization of shopware

bin/console database:migrate --all
bin/console database:migrate-destructive --all
bin/console framework:create:tenant --tenant-id=__TENANT_ID__

bin/console plugin:update

bin/console rest:user:create admin --password=shopware --tenant-id=__TENANT_ID__

php bin/console sales-channel:create --tenant-id=__TENANT_ID__ --id=20080911ffff4fffafffffff19830531
php bin/console sales-channel:create:storefront --tenant-id=__TENANT_ID__ --url=__APP_URL__

APP_ENV=prod php bin/console cache:clear
php bin/console cache:clear
