#!/usr/bin/env bash
#DESCRIPTION: initialization of your environment

# generate default SSL private/public key
php dev-ops/generate_ssl.php

composer require "shopware/platform:__PLATFORM_BRANCH__-dev" --no-interaction --no-suggest --no-scripts

INCLUDE: ./../../common/actions/init-database.sh

bin/console framework:migrate --all
bin/console framework:create:tenant --tenant-id=__TENANT_ID__
bin/console rest:user:create admin --password=shopware --tenant-id=__TENANT_ID__
bin/console sales-channel:create --tenant-id=__TENANT_ID__ --id=20080911ffff4fffafffffff19830531

INCLUDE: ./../../common/actions/.init-test-database.sh
