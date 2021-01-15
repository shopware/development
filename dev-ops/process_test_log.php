<?php declare(strict_types=1);

namespace Shopware\DevOps\Common\Script;

/**
 * MERGE the log files from different test suites into one cohesive file
 */

$phpArtifactsPath = __DIR__ . '/../build/artifacts/backend';

/** @var string[] $logs */
$logs = [];

foreach (glob($phpArtifactsPath . '/phpunit.*.junit.xml') as $file) {
    $logs[] = file($file);
}

if ($logs === []) {
    trigger_error('NO RESULT FILES FOUND', E_USER_ERROR);
    exit(-1);
}

$mergedLog = <<<HEAD
<?xml version="1.0" encoding="UTF-8"?>
   <testsuites>
HEAD;


echo "\n### Merging logs\n";
foreach ($logs as $log) {
    unset($log[count($log) - 1]);
    unset($log[0]);
    unset($log[1]);

    $mergedLog .= implode(PHP_EOL, $log);
}

$mergedLog .= '</testsuites>';

file_put_contents($phpArtifactsPath . '/../phpunit.junit.xml', $mergedLog);

echo "### Validating Build\n";
$hasErrors = strpos($mergedLog, '<error ') !== false;
$hasFailure = strpos($mergedLog, '<failure ') !== false;

if ($hasErrors || $hasFailure) {
    echo "!!! SOME ERRORS FOUND\n";
    exit(-1);
}

echo "### NO ERRORS FOUND\n";
exit;
