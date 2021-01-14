<?php declare(strict_types=1);

namespace Shopware\DevOps\Common\Script;

/**
 * MERGE the generated coverage from different test suites into one cohesive file
 */
require_once __DIR__ . '/../vendor/autoload.php';

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Clover;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;

$phpArtifactsPath = __DIR__ . '/../build/artifacts/backend';

/** @var CodeCoverage[] $coverages */
$coverages = [];

echo "\n### Loading results\n";
foreach(glob($phpArtifactsPath . '/phpunit.*.php') as $file) {
    $coverages[] = require $file;
}

if($coverages === []) {
    trigger_error('NO COVERAGE FILES FOUND', E_USER_ERROR);
    exit -1;
}

/** @var CodeCoverage $first */
$first = array_pop($coverages);

echo "\n### Merging results\n\n";
foreach($coverages as $coverage) {
    $first->merge($coverage);
}

echo "### Writing clover\n";
$writer = new Clover;
$writer->process($first, $phpArtifactsPath . '/../phpunit.clover.xml');

echo "### Writing HTML\n";
$writer = new Facade;
$writer->process($first, $phpArtifactsPath . '/phpunit-coverage-html');


