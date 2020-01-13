#!/usr/bin/env bash
#DESCRIPTION: fix code style in src folder

php dev-ops/analyze/vendor/bin/ecs check --fix vendor/shopware/platform/src --config vendor/shopware/platform/easy-coding-standard.yml
