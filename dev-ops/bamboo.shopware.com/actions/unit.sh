#!/usr/bin/env bash

vendor/bin/phpunit --configuration vendor/shopware/platform/phpunit.xml.dist --log-junit build/artifacts/phpunit.junit.xml --coverage-clover build/artifacts/phpunit.clover.xml