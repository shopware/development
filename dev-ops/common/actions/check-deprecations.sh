#!/usr/bin/env bash
#DESCRIPTION: checks for deprecations, use --VERSION option to check deprecations for a specific version

VERSION=__VERSION__ vendor/bin/phpunit --stop-on-failure --stop-on-error ./platform/src/Core/Framework/Test/DeprecatedTagTest.php

