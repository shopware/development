#!/usr/bin/env bash

vendor/bin/phpunit --configuration vendor/shopware/platform/phpunit.xml.dist --log-junit build/artifacts/phpunit.junit.xml
vendor/bin/phpunit --configuration phpunit.xml.dist --log-junit build/artifacts/phpunit_development.junit.xml