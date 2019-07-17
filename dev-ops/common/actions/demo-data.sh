#!/usr/bin/env bash
#DESCRIPTION: creates a demo data set

APP_ENV=prod bin/console framework:demodata
APP_ENV=prod bin/console dal:refresh:index

# clear cache
INCLUDE: ./cache.sh
