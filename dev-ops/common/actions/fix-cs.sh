#!/usr/bin/env bash
#DESCRIPTION: fix code style in src folder

if [ -f "platform/easy-coding-standard.php" ]; then php dev-ops/analyze/vendor/bin/ecs check --fix platform/src --config platform/easy-coding-standard.php; else php dev-ops/analyze/vendor/bin/ecs check --fix platform/src --config platform/easy-coding-standard.yml; fi;
if [ -f "platform/easy-coding-standard.php" ]; then php dev-ops/analyze/vendor/bin/ecs check --fix src --config platform/easy-coding-standard.php; else php dev-ops/analyze/vendor/bin/ecs check --fix platform/src --config platform/easy-coding-standard.yml; fi;
