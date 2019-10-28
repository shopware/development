#!/usr/bin/env bash
#DESCRIPTION: build the component library for production

PROJECT_ROOT=__PROJECT_ROOT__ npm run --prefix vendor/shopware/platform/src/Administration/Resources/app/administration/build/nuxt-component-library/ generate