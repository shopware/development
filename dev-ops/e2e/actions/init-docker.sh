#!/usr/bin/env bash
#DESCRIPTION: installs the dependencies for the e2e tests using npm in cypress container

docker exec -u __USERKEY__ __CYPRESS_ID__ npm clean-install --prefix app/vendor/shopware/platform/src/Administration/Resources;
docker exec -u __USERKEY__ __CYPRESS_ID__ npm run --prefix app/vendor/shopware/platform/src/Administration/Resources lerna -- bootstrap;
