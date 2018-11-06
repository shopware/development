#!/usr/bin/env bash
#DESCRIPTION: creates a demo data set

APP_ENV=prod bin/console framework:demodata --products=500 --categories=5 --manufacturers=25 --tenant-id=__TENANT_ID__
APP_ENV=prod bin/console dbal:refresh:index --tenant-id=__TENANT_ID__
