#!/usr/bin/env bash
#DESCRIPTION: generates the api documentation using jsdoc

PROJECT_ROOT=__PROJECT_ROOT__ npm run --prefix vendor/shopware/platform/src/Administration/Resources/app/administration/ generate-api-docs
