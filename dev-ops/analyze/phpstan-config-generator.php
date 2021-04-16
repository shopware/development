<?php declare(strict_types=1);

use Composer\InstalledVersions;
use Shopware\Core\Framework\Plugin\KernelPluginLoader\StaticKernelPluginLoader;
use Shopware\Development\Kernel;
use Symfony\Component\Dotenv\Dotenv;

$autoLoadFile = __DIR__ . '/../../vendor/autoload.php';
$classLoader = require $autoLoadFile;
(new Dotenv())->usePutenv()->load(__DIR__ . '/../../.env');

$shopwareVersion = InstalledVersions::getVersion('shopware/platform') . '@' . InstalledVersions::getReference('shopware/platform');

$pluginLoader = new StaticKernelPluginLoader($classLoader);

$cacheId = 'default';

$kernel = new Kernel('dev', true, $pluginLoader, $cacheId, $shopwareVersion);
$kernel->boot();
$projectDir = $kernel->getProjectDir();
$cacheDir = $kernel->getCacheDir();

$relativeCacheDir = str_replace($projectDir, '', $cacheDir);

$phpStanConfigDist = file_get_contents(__DIR__ . '/../../platform/phpstan.neon.dist');
if ($phpStanConfigDist === false) {
    throw new RuntimeException('phpstan.neon.dist file not found');
}

// because the cache dir is hashed by Shopware, we need to set the PHPStan config dynamically
$phpStanConfig = str_replace(
    [
        "\n        # the placeholder \"%ShopwareHashedCacheDir%\" will be replaced on execution by dev-ops/analyze/phpstan-config-generator.php script",
        '%ShopwareHashedCacheDir%',
    ],
    [
        '',
        $relativeCacheDir,
    ],
    $phpStanConfigDist
);

file_put_contents(__DIR__ . '/../../platform/phpstan.neon', $phpStanConfig);
