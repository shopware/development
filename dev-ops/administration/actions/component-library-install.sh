#!/usr/bin/env bash
#DESCRIPTION: installs the dependencies for the component library tests using npm

PROJECT_ROOT=__PROJECT_ROOT__ npm clean-install --prefix vendor/shopware/platform/src/Administration/Resources/nuxt-component-library/
