#!/usr/bin/env bash
#DESCRIPTION: initialization of shopware

bin/console framework:create:tenant --tenant-id=__TENANT_ID__

bin/console plugin:update

bin/console rest:user:create admin --password=shopware --tenant-id=__TENANT_ID__

php bin/console touchpoint:create --tenant-id=__TENANT_ID__ --id=20080911ffff4fffafffffff19830531
php bin/console touchpoint:create:storefront --tenant-id=__TENANT_ID__ --url=__APP_URL__

php bin/console cache:clear -eprod
php bin/console cache:clear
