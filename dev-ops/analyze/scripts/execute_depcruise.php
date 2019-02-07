<?php

require_once __DIR__ . '/_common.php.inc.php';


function createProcess(string $depfileName, string $depfilePath)
{
    return \Symfony\Component\Process\Process::fromShellCommandline(
        __DIR__ . '/../node_modules/.bin/depcruise'
        . ' --output-type=dot'
        . ' ' . $depfilePath
        . ' | dot -T svg > build/artifacts/' . $depfileName . '.svg'
        , __DIR__ . '/../../..'
    );
}


$adminPath = 'vendor/shopware/platform/src/Administration/Resources/administration';

$moduleFinder = new \Symfony\Component\Finder\Finder();
$moduleFinder
    ->directories()
    ->depth(0)
    ->in($adminPath . '/src/module');

foreach ($moduleFinder as $fileInfo) {
    $fileName = 'depcruise.module.' . $fileInfo->getBasename();
    AnalyzeCommons::putImageConfig($fileName, $fileName, AnalyzeCommons::normalizeFileInfo($fileInfo));
    AnalyzeCommons::start($fileName, createProcess($fileName, $fileInfo->getRealPath()));
}

$appFinder = new \Symfony\Component\Finder\Finder();
$appFinder
    ->directories()
    ->depth(0)
    ->in($adminPath . '/src');

foreach ($appFinder as $fileInfo) {
    $fileName = 'depcruise.app.' . $fileInfo->getBasename();
    AnalyzeCommons::putImageConfig($fileName, $fileName, AnalyzeCommons::normalizeFileInfo($fileInfo));
    AnalyzeCommons::start($fileName, createProcess($fileName, $fileInfo->getRealPath()));
}

AnalyzeCommons::wait();
