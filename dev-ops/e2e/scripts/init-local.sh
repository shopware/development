#!/usr/bin/env bash

# Constants
CYPRESS_ENV=$1

npm clean-install --prefix platform/src/"$CYPRESS_ENV"/Resources/app/"${CYPRESS_ENV,,}"/test/e2e/;
