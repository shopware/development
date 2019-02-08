#!/usr/bin/env bash
#DESCRIPTION: Generate reports from static analysis tools

I: rm -R ./build/artifacts/*

composer dump-autoload --optimize --no-interaction

D: dev-ops/analyze/vendor/bin/phpmd
   __PLATFORM_DIR__/src/
   xml
   design,codesize,cleancode
   --ignore-violations-on-exit
   --reportfile ./build/artifacts/phpmd.pmd.xml

D: dev-ops/analyze/vendor/bin/phpmd
   __PLATFORM_DIR__/src/
   html
   design,codesize,cleancode
   --ignore-violations-on-exit
   --reportfile ./build/artifacts/phpmd.pmd.html

D: dev-ops/analyze/vendor/bin/phpmetrics
   --report-violations="./build/artifacts/php.metrics.violations.xml"
   --report-json="./build/artifacts/php.metrics.phpmetrics.xml"
   --report-html="./build/artifacts/php.metrics-html"
   __PLATFORM_DIR__/

D: php -r "file_put_contents(__DIR__(sic!) . '/build/artifacts/classmap.json', json_encode(['prefix' => __DIR__(sic!) . '__PLATFORM_DIR__/', 'map' => require __DIR__(sic!) . '/vendor/composer/autoload_classmap.php']));"

D: cd dev-ops/analyze && npm run-script complexity __PLATFORM_DIR__
D: php dev-ops/analyze/scripts/execute_depcruise.php
D: cd dev-ops/analyze && node_modules/.bin/jscpd __PLATFORM_DIR__/src/Administration/Resources/administration/src/

D: php dev-ops/analyze/scripts/generate_deptrac_core.php __PLATFORM_DIR__
D: php dev-ops/analyze/scripts/execute_deptrac.php
D: php dev-ops/analyze/scripts/put_commit.php

D: dev-ops/analyze/vendor/bin/phploc
   --log-xml="./build/artifacts/phploc.xml"
   __PLATFORM_DIR__/

I: dev-ops/analyze/vendor/bin/phpcpd
   --fuzzy
   --progress
   --log-pmd="./build/artifacts/phpcpd.pmd.xml"
   __PLATFORM_DIR__
