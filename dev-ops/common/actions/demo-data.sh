#!/usr/bin/env bash
#DESCRIPTION: creates a demo data set

APP_ENV=prod bin/console framework:demodata --products=500 --categories=5 --manufacturers=25
APP_ENV=prod bin/console dbal:refresh:index
