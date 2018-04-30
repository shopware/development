#!/usr/bin/env bash
#DESCRIPTION: generates the component documentation using storybook

npm run --prefix vendor/shopware/platform/src/Administration/Resources/administration/ storybook-generate
