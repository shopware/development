#!/usr/bin/env bash
#DESCRIPTION: execute unit tests

php vendor/bin/phpunit --log-junit=build/artifacts/junit.xml
