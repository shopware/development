#!/usr/bin/env sh

#DESCRIPTION: opens e2e tests runner

INCLUDE: ./../../common/actions/cache.sh

npm clean-install --prefix vendor/shopware/platform/src/__CYPRESS_ENV__/Resources/app/__CYPRESS_FOLDER__/test/e2e/;
if [[ -z "__CYPRESS_LOCAL__" ]]; then ./psh.phar e2e:open-docker --CYPRESS_ENV="__CYPRESS_ENV__" --CYPRESS_FOLDER="__CYPRESS_FOLDER__"; else ./psh.phar e2e:open-local --CYPRESS_ENV="__CYPRESS_ENV__" --CYPRESS_FOLDER="__CYPRESS_FOLDER__"; fi
