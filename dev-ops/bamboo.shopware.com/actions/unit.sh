#!/usr/bin/env bash

php -d pcov.enabled=1
   vendor/bin/phpunit
   --configuration vendor/shopware/platform/phpunit.xml.dist
   --log-junit build/artifacts/phpunit.junit.xml
   --coverage-clover build/artifacts/phpunit.clover.xml
