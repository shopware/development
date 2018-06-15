#!/usr/bin/env bash
#DESCRIPTION: initialization of shopware

bin/console framework:create:tenant --tenant-id=ffffffffffffffffffffffffffffffff

bin/console plugin:update

bin/console rest:user:create admin --password=shopware --tenant-id=ffffffffffffffffffffffffffffffff

php bin/console touchpoint:create --tenant-id=ffffffffffffffffffffffffffffffff --id=ffffffffffffffffffffffffffffffff
php bin/console touchpoint:create:storefront --tenant-id=ffffffffffffffffffffffffffffffff --url=__APP_URL__

php bin/console cache:clear
