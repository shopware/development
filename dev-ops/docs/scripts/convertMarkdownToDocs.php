<?php

use Shopware\Docs\Convert\CredentialsService;
use Shopware\Docs\Convert\Document;
use Shopware\Docs\Convert\DocumentTree;
use Shopware\Docs\Convert\WikiApiService;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

require __DIR__ . '/../../../vendor/autoload.php';

$docsPath = __DIR__ . '/../../../vendor/shopware/platform/src/Docs';

const CATEGORY_SITE_FILENAME = '__categoryInfo.md';
const BLACKLIST = 'article.blacklist';
const CREDENTIAL_PATH = __DIR__ . '/wiki.secret';

function readAllFiles(array $files): array
{
    $allContents = [];
    foreach ($files as $file) {
        $content = file_get_contents($file);
        if ($content === '') {
            continue;
        }
        $allContents[$file] = $content;
    }

    return $allContents;
}

function loadDocuments(string $fromPath, string $baseUrl, array $blacklist): DocumentTree
{
    $files = (new Finder())
        ->files()
        ->in($fromPath)
        ->sortByName()
        ->depth('>=1')
        ->name('*.md');

    $documents = [];

    /** @var SplFileInfo $file */
    foreach ($files as $file) {
        foreach ($blacklist as $blacklistedFile) {
            $blacklistedFile = trim($blacklistedFile);
            if (strpos($file->getRelativePathname(), $blacklistedFile) === 0) {
                echo 'Blacklisted ' . $file->getRelativePathname() . "\n";
                continue 2;
            }
        }

        $documents[$file->getRelativePathname()] = new Document(
            $file,
            $file->getFilename() === CATEGORY_SITE_FILENAME,
            $baseUrl
        );
    }

    //compile into tree
    $tree = new DocumentTree();
    /** @var Document $document */
    foreach ($documents as $path => $document) {
        if ($document->isCategory()) {
            $parentPath = \dirname($document->getFile()->getRelativePath()) . '/' . CATEGORY_SITE_FILENAME;
        } else {
            $parentPath = $document->getFile()->getRelativePath() . '/' . CATEGORY_SITE_FILENAME;
        }

        $tree->add($document);

        //find parent
        if (!isset($documents[$parentPath])) {
            // found a root, but not necessarily THE root so we skip here
            continue;
        }

        /** @var Document $parent */
        $parent = $documents[$parentPath];
        $document->setParent($parent);
        $parent->addChild($document);
    }

    $root = new Document(
        new \Symfony\Component\Finder\SplFileInfo(
            $fromPath . '/__categoryInfo.md',
            '',
            '__categoryInfo.md'
        ),
        true,
        $baseUrl
    );
    $tree->setRoot($root);

    return $tree;
}

(new Application('convert', '1.0.0'))
    ->register('convert')
    ->addOption('input', 'i', InputOption::VALUE_REQUIRED, 'The path to parse for markdown files.', './platform/src/Docs/Resources/current/')
    ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'The path in which the resulting HTML files will be saved.')
    ->addOption('baseurl', 'u', InputOption::VALUE_REQUIRED, '', '/shopware-platform')
    ->addOption('sync', 's', InputOption::VALUE_NONE)
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $inPath = $input->getOption('input');
        $outPath = $input->getOption('output');
        $baseUrl = $input->getOption('baseurl');
        $isSync = $input->getOption('sync');

        $blacklist = [];
        $blacklistFile = $inPath . BLACKLIST;
        if (is_file($blacklistFile)) {
            $blacklist = file($blacklistFile);
        }

        $output->writeln('Scanning \"' . $inPath . '" for .md files ...');
        $tree = loadDocuments($inPath, $baseUrl, $blacklist);
        $output->writeln('Read ' . \count($tree->getAll()) . ' markdown files');

        if ($outPath === null) {
            throw new \RuntimeException('No output path specified');
        }

        $fs = new Filesystem();

        /** @var Document $document */
        foreach (array_merge($tree->getAll(), [$tree->getRoot()]) as $document) {
            $path = $outPath . '/' . $document->getFile()->getRelativePath();

            $htmlFile = $path . '/' . $document->getFile()->getBasename('.md') . '.html';
            $phpFile = $path . '/' . $document->getFile()->getBasename('.md') . '.php';

            $fs->mkdir($outPath);
            $fs->dumpFile($htmlFile, $document->getHtml()->render($tree)->getContents());
            $fs->dumpFile($phpFile, '<?php return ' . var_export($document->getMetadata()->toArray($tree), true) . ';');
        }

        $credentialsService = new CredentialsService();

        if (!$isSync || !$credentialsService->credentialsFileExists()) {
            return 0;
        }

        $syncService = new WikiApiService($credentialsService->getCredentials(), $this->environment);
        $syncService->syncFilesWithServer($tree);
    })
    ->getApplication()
    ->setDefaultCommand('convert')
    ->run();
