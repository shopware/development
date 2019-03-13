#!/usr/bin/env php
<?php

$dir = $argv[1];

if (!is_dir($dir)) {
    echo "FATAL: First parameter must be a directory\n";
    die(5);
}

$scan = function ($dir, callable $callable) {
    foreach (scandir($dir, SCANDIR_SORT_ASCENDING) as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $callable($file, $dir . '/' . $file);
    }
};
$contents = "<$dir>\n";


$renderFiles = function (string $file, string $path) use (&$contents) {
    if (is_dir($path)) {
        return;
    }

    $contents .= "└── $file\n";
};

$renderDirectories = function (string $file, string $path) use (&$contents) {
    if (!is_dir($path)) {
        return;
    }

    $contents .= "└── $file\n";
};

$scan($dir, $renderDirectories);
$scan($dir, $renderFiles);


echo "```\n";
echo $contents;
echo "```\n";
