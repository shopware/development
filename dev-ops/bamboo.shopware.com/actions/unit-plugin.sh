#!/usr/bin/env bash

vendor/bin/phpunit --configuration custom/plugins/__PLUGIN__/phpunit.xml.dist --log-junit build/artifacts/phpunit.junit.xml --coverage-clover build/artifacts/phpunit.clover.xml
