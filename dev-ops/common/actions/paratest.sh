#!/usr/bin/env bash

vendor/bin/paratest
    --configuration platform/phpunit.xml.dist
    --no-coverage
    --testsuite=paratest
    --exclude-group=skip-paratest,needsWebserver
    --processes $(nproc)
    --runner WrapperRunner
