#!/usr/bin/env bash
#DESCRIPTION: Install database, dependencies and create tenant with default data set

# generate default SSL private/public key
php dev-ops/generate_ssl.php

INCLUDE: ./init-composer.sh
INCLUDE: ./init-database.sh
INCLUDE: ./init-shopware.sh
INCLUDE: ./.init-test-database.sh
