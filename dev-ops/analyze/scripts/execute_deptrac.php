<?php

require_once __DIR__ . '/_common.php.inc.php';

function createProcess(string $depfileName, string $depfilePath): \Symfony\Component\Process\Process
    {
        return new \Symfony\Component\Process\Process([
            __DIR__ . '/../deptrac.phar',
            'analyze',
            '--no-cache',
            '--formatter-junit=true',
            '--formatter-junit-dump-xml=./build/artifacts/' . $depfileName . '.xml',
            '--formatter-graphviz=true',
            '--formatter-graphviz-dump-image=./build/artifacts/' . $depfileName . '.png',
            '--formatter-full-log=true',
            '--formatter-full-log-file=./build/artifacts/' .$depfileName . '.json',
            $depfilePath,
        ], __DIR__ . '/../../..');
    }

$path = __DIR__ . '/../tmp/depfiles';
foreach(scandir($path, SCANDIR_SORT_ASCENDING) as $depfile) {
    if (pathinfo($depfile, PATHINFO_EXTENSION) !== 'yml') {
        continue;
    }
    $fileName = pathinfo($depfile, PATHINFO_FILENAME);
    $filePath = "$path/$depfile";

    $yaml = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($filePath));
    $configPath = substr(realpath(min($yaml['paths'])), strlen('/app/vendor/shopware/platform'));

    AnalyzeCommons::putImageConfig($fileName, $fileName, $configPath);
    AnalyzeCommons::start($fileName, createProcess($fileName, $filePath));
}

AnalyzeCommons::wait();
