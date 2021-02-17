#!/usr/bin/env bash
#DESCRIPTION: execute unit tests and generate coverage information

I: rm -R build/artifacts/backend/ || true

for TEST_SUITE in __PHP_TEST_SUITES__; do
    if [ -n "__PHP_TEST_COVERAGE__" ]; then
        php -d pcov.directory=./platform
            -d pcov.enabled=1
            vendor/bin/phpunit
            --configuration platform/phpunit.xml.dist
            --log-junit build/artifacts/backend/phpunit.$TEST_SUITE.junit.xml
            --coverage-php build/artifacts/backend/phpunit.$TEST_SUITE.php
            --colors=never
            --testsuite $TEST_SUITE; else
        php vendor/bin/phpunit
            --configuration platform/phpunit.xml.dist
            --colors=never
            --log-junit build/artifacts/backend/phpunit.$TEST_SUITE.junit.xml
            --testsuite $TEST_SUITE; fi
    done

if [ -n "__PHP_TEST_COVERAGE__" ]; then
    php -d pcov.enabled=1 dev-ops/process_phpunit_coverage.php build/artifacts/backend/;
    php -d pcov.enabled=1 dev-ops/process_sum_coverage.php build/artifacts/backend/;fi

php dev-ops/process_test_log.php build/artifacts/backend/;
