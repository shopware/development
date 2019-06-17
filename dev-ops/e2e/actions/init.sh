#!/usr/bin/env sh

#DESCRIPTION: installs the dependencies for the e2e tests using npm in cypress container

if [[ -z "__CYPRESS_LOCAL__" ]]; then ./psh.phar e2e:init-docker; else ./psh.phar e2e:init-local; fi
