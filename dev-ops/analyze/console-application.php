<?php declare(strict_types=1);

use Shopware\Core\Framework\Plugin\KernelPluginLoader\StaticKernelPluginLoader;
use Shopware\Development\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

$classLoader = require __DIR__ . '/../../vendor/autoload.php';

(new Dotenv(true))->load(__DIR__ . '/../../.env');

$pluginLoader = new StaticKernelPluginLoader($classLoader);

$kernel = new Kernel('phpstan_dev', true, $pluginLoader, 'phpstan-test-cache-id');

return new Application($kernel);
