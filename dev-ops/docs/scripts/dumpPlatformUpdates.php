<?php

use Shopware\Docs\Convert\DocumentTree;
use Shopware\Docs\Convert\PlatformUpdatesDocument;
use Symfony\Component\Finder\Finder;

require __DIR__ . '/../../../vendor/autoload.php';

const TEMPLATE_HEADER = <<<EOD
[titleEn]: <>(Recent updates)
[__RAW__]: <>(__RAW__)

<p>Here you can find recent information about technical updates and news regarding <a href="https://github.com/shopware/platform">shopware platform</a>.</p>

<p><strong>New: Our public admin component library for easy scaffolding of your admin modules</strong></p>

<p><a href="https://component-library.shopware.com/">https://component-library.shopware.com</a></p>

EOD;

const TEMPLATE_MONTH = <<<EOD
<h2>%s</h2>

EOD;

const TEMPLATE_HEADLINE = <<<EOD
<h3>%s: %s</h3>

EOD;

$docsPath = __DIR__ . '/../../../vendor/shopware/platform/src/Docs';
$platformUpdatesPath = $docsPath . '/Resources/platform-updates';
$files = (new Finder())
    ->in($platformUpdatesPath)
    ->files()
    ->sortByName()
    ->depth('0')
    ->name('*.md')
    ->exclude('_archive.md')
    ->getIterator();

$filesInOrder = array_reverse(iterator_to_array($files));

$filesSorted = [];

foreach ($filesInOrder as $file) {
    $baseName = $file->getBasename('.md');

    if ($baseName === '_archive') {
        continue;
    }

    echo '* Rendering: ' . $baseName . PHP_EOL;

    $parts = explode('-', $baseName);

    if (\count($parts) < 3) {
        throw new \RuntimeException(sprintf('File %s is invalidly named', $file->getRelativePathname()));
    }

    $date = \DateTimeImmutable::createFromFormat('Y-m-d', implode('-', \array_slice($parts, 0, 3)));

    $month = $date->format('Y-m');

    if (!isset($filesSorted[$month])) {
        $filesSorted[$month] = [];
    }

    $filesSorted[$month][] = new PlatformUpdatesDocument($date, $file, false, '');
}

$rendered = [TEMPLATE_HEADER];
foreach ($filesSorted as $month => $documents) {
    $rendered[] = sprintf(
        TEMPLATE_MONTH,
        \DateTimeImmutable::createFromFormat('Y-m', $month)->format('F Y')
    );

    /** @var PlatformUpdatesDocument $document */
    foreach ($documents as $document) {
        $rendered[] = sprintf(
            TEMPLATE_HEADLINE,
            $document->getDate()->format('Y-m-d'),
            $document->getMetadata()->getTitleEn()
        );

        $rendered[] = $document->getHtml()->render(new DocumentTree())->getContents();
    }
}

$archivedFile = $platformUpdatesPath . '/_archive.md';

if (file_exists($archivedFile)) {
    echo 'Loading archive file' . PHP_EOL;
    $rendered[] = file_get_contents($archivedFile);
}

$fileContents = implode(PHP_EOL, $rendered);
file_put_contents($docsPath . '/Resources/current/1-getting-started/40-recent-updates/__categoryInfo.md', $fileContents);
echo $docsPath . '/Resources/current/1-getting-started/40-recent-updates/__categoryInfo.md'.PHP_EOL;

echo 'Done' . PHP_EOL;
