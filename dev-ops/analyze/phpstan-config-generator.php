<?php declare(strict_types=1);

use PackageVersions\Versions;
use Shopware\Development\Kernel;
use Symfony\Component\Dotenv\Dotenv;

$classLoader = require __DIR__ . '/../../vendor/autoload.php';
(new Dotenv(true))->load(__DIR__ . '/../../.env');

$shopwareVersion = Versions::getVersion('shopware/platform');

$kernel = new Kernel('dev', true, $classLoader, $shopwareVersion);
$kernel->boot();
$projectDir = $kernel->getProjectDir();
$cacheDir = $kernel->getCacheDir();

$relativeCacheDir = str_replace($projectDir, '', $cacheDir);

$phpStanConfigDist = file_get_contents(__DIR__ . '/../../platform/phpstan.neon.dist');

// because the cache dir is hashed by Shopware, we need to set the PHPStan config dynamically
$phpStanConfig = str_replace(
    [
        "\n        # the placeholder \"%ShopwareHashedCacheDir%\" will be replaced on execution by dev-ops/analyze/phpstan-config-generator.php script",
        '%ShopwareHashedCacheDir%',
    ],
    [
        '',
        $relativeCacheDir
    ],
    $phpStanConfigDist
);

file_put_contents(__DIR__ . '/../../platform/phpstan.neon', $phpStanConfig);
