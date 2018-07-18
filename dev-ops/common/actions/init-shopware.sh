#!/usr/bin/env bash
#DESCRIPTION: initialization of shopware

bin/console framework:create:tenant --tenant-id=20080911ffff4fffafffffff19830531

bin/console plugin:update

bin/console rest:user:create admin --password=shopware --tenant-id=20080911ffff4fffafffffff19830531

php bin/console touchpoint:create --tenant-id=20080911ffff4fffafffffff19830531 --id=20080911ffff4fffafffffff19830531
php bin/console touchpoint:create:storefront --tenant-id=20080911ffff4fffafffffff19830531 --url=__APP_URL__

php bin/console cache:clear
