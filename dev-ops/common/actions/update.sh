#!/usr/bin/env bash
#DESCRIPTION: Update Shopware after deployment

INCLUDE: ./cache.sh

bin/console plugin:refresh

bin/console database:migrate --all core

bin/console dal:refresh:index

bin/console scheduled-task:register

bin/console theme:refresh
