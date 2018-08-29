#!/usr/bin/env bash
#DESCRIPTION: Run deptrac on core

I: vendor/shopware/platform/bin/deptrac.phar analyze vendor/shopware/platform/depfile.yml
I: vendor/shopware/platform/bin/deptrac.phar analyze vendor/shopware/platform/depfile.components.yml