<?php

use Shopware\Docs\Command\ConvertMarkdownDocsCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/../../../vendor/autoload.php';

$docsPath = __DIR__ . '/../../../vendor/shopware/platform/src/Docs';

(new Application('convert', '1.0.0'))
    ->add(new ConvertMarkdownDocsCommand('convert'))
    ->getApplication()
    ->setDefaultCommand('convert')
    ->run();
