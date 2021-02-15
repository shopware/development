#!/usr/bin/env bash
#DESCRIPTION: Everything from "init" + demo data generation + administration build/deploy

# generate default SSL private/public key
php dev-ops/generate_ssl.php

INCLUDE: ./cache.sh
INCLUDE: ./init-composer.sh
INCLUDE: ./init-database.sh
INCLUDE: ./init-shopware.sh
INCLUDE: ./init-test-databases.sh
INCLUDE: ./demo-data.sh
INCLUDE: ../../administration/actions/init.sh
INCLUDE: ../../storefront/actions/init.sh
bin/console assets:install
bin/console theme:compile
