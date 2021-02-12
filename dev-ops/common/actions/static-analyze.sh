#!/usr/bin/env bash
#DESCRIPTION: Run static code analysis on core

php dev-ops/analyze/phpstan-config-generator.php
php dev-ops/analyze/vendor/bin/phpstan analyze --autoload-file=dev-ops/analyze/vendor/autoload.php --configuration platform/phpstan.neon

# If composer.lock is not a file, create it with composer, because if it is not present, the caching of Psalm does not work. See https://github.com/vimeo/psalm/issues/4941
if [ ! -f "platform/composer.lock" ]; then composer update --no-install -d platform -q; fi
php dev-ops/analyze/vendor/bin/psalm --config=platform/psalm-new.xml --threads=2 --show-info=false
