#!/usr/bin/env bash
#DESCRIPTION: fix code style in src folder

if [ -e platform/ecs.php ]; then cd platform; if [ ! -r vendor/autoload.php ]; then composer update; fi; composer run ecs -- --fix src/; fi
if [ ! -e platform/ecs.php ]; then php dev-ops/analyze/vendor/bin/ecs check --fix platform/src --config platform/easy-coding-standard.php; fi
