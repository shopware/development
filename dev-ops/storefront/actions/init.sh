#!/usr/bin/env bash
#DESCRIPTION: install dependencies and build for production

INCLUDE: ./install-dependencies.sh
INCLUDE: ./build.sh
bin/console theme:change Storefront --all