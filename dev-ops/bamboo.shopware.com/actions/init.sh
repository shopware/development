#!/usr/bin/env bash
#DESCRIPTION: initialization of your environment

# generate default SSL private/public key
php dev-ops/generate_ssl.php

composer require "shopware/platform:dev-__PLATFORM_BRANCH__" --no-interaction --no-suggest --no-scripts

INCLUDE: ./../../common/actions/init-database.sh

bin/console database:migrate --all
bin/console database:migrate-destructive --all
bin/console rest:user:create admin --password=shopware
bin/console sales-channel:create --id=20080911ffff4fffafffffff19830531
bin/console sales-channel:create:storefront --url=__APP_URL__

bin/console cache:clear

INCLUDE: ./../../common/actions/init-test-databases.sh
