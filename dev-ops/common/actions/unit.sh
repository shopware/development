#!/usr/bin/env bash
#DESCRIPTION: execute unit tests and generate coverage information

php -d pcov.enabled=1
   vendor/bin/phpunit
   --configuration vendor/shopware/platform/phpunit.xml.dist
   --log-junit build/artifacts/phpunit.junit.xml
   --coverage-clover build/artifacts/phpunit.clover.xml
   --coverage-html build/artifacts/phpunit-coverage-html
