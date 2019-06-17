#!/usr/bin/env sh

if [[ -z "__CYPRESS_LOCAL__" ]]; then ./psh.phar e2e:run-docker --CYPRESS_PARAMS="__CYPRESS_PARAMS__" --CYPRESS_ENV="__CYPRESS_ENV__"; else ./psh.phar e2e:run-local --CYPRESS_PARAMS="__CYPRESS_PARAMS__"  --CYPRESS_ENV="__CYPRESS_ENV__"; fi
