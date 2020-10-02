#!/usr/bin/env bash
#DESCRIPTION: Run static code analysis on core

php dev-ops/analyze/phpstan-config-generator.php
php dev-ops/analyze/vendor/bin/phpstan analyze --autoload-file=dev-ops/analyze/vendor/autoload.php --configuration platform/phpstan.neon

if [ -f "platform/psalm.new.xml" ]; then php dev-ops/analyze/vendor/bin/psalm --config=platform/psalm.new.xml --threads=2 --diff --show-info=false; else php dev-ops/analyze/vendor/bin/psalm --config=platform/psalm.xml --threads=2 --diff --show-info=false; fi;
