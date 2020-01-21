#!/usr/bin/env bash

# Constants
CYPRESS_ENV=$1

npm clean-install --prefix platform/src/"$CYPRESS_ENV"/Resources/app/"$(echo $CYPRESS_ENV | tr '[:upper:]' '[:lower:]')"/test/e2e/;
