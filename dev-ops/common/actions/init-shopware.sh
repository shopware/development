#!/usr/bin/env bash
#DESCRIPTION: initialization of shopware

INCLUDE: ./cache.sh

# This files are not required anymore after a reinstall
rm -rf public/bundles/*
rm -rf public/media/*
rm -rf public/sitemap/*
rm -rf public/theme/*
rm -rf public/thumbnail/*
rm -rf files/export/*
rm -rf files/media/*
rm -rf var/log/*

bin/console database:migrate --all core
bin/console database:migrate-destructive --all core --version-selection-mode=all

bin/console dal:refresh:index

bin/console scheduled-task:register

bin/console plugin:refresh

bin/console user:create admin --password=shopware --admin

bin/console sales-channel:create:storefront --url='__APP_URL__'

bin/console theme:refresh
bin/console assets:install
