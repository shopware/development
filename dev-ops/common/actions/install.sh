#!/usr/bin/env bash
#DESCRIPTION: Initialization of the SaaS project

# generate default SSL private/public key
php dev-ops/generate_ssl.php

INCLUDE: ./init-composer.sh
INCLUDE: ./init-database.sh
INCLUDE: ./init-shopware.sh
INCLUDE: ./demo-data.sh
INCLUDE: ../../administration/actions/init.sh
INCLUDE: ../../administration/actions/build.sh
bin/console assets:install
