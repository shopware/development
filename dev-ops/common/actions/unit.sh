#!/usr/bin/env bash
#DESCRIPTION: execute unit tests

vendor/bin/phpunit --configuration vendor/shopware/platform/phpunit.xml.dist --log-junit build/artifacts/phpunit.junit.xml
