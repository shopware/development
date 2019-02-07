<?php

require_once __DIR__ . '/_common.php.inc.php';

$parser = new Tivie\GitLogParser\Parser();
$parser->setGitDir(__DIR__ . '/../../../vendor/shopware/platform/');
$parser->getCommand()->addArgument(new Tivie\Command\Argument('-n 1'));

$commits = $parser->parse();

AnalyzeCommons::putArtifact('commit.json', json_encode($commits[0]));
