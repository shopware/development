#!/usr/bin/env sh

#DESCRIPTION: opens e2e tests runner

INCLUDE: ./../../common/actions/cache.sh

bin/console administration:dump:features

if [[ -z "__CYPRESS_LOCAL__" ]]; then ./psh.phar e2e:open-docker; else ./psh.phar e2e:open-local; fi