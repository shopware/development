#!/usr/bin/env bash

I: phpdbg -qrr vendor/bin/phpunit
   --log-junit runtime/build/artifacts/phpunit.junit.xml
   --coverage-clover runtime/build/artifacts/phpunit.coverage.xml
   --coverage-xml runtime/build/artifacts/php-coverage-xml
   --coverage-html runtime/build/artifacts/php-coverage-html

phpdbg -qrr dev-ops/quality/vendor/bin/infection
   --configuration="./dev-ops/quality/infection.json.dist"
   --threads="8"
   --coverage="/app/runtime/build/artifacts"
