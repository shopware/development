#!/usr/bin/env bash
#DESCRIPTION: Installs the node.js dependencies

npm --prefix vendor/shopware/platform/src/Storefront/Resources/app/storefront/ clean-install
node vendor/shopware/platform/src/Storefront/Resources/app/storefront/copy-to-vendor.js
