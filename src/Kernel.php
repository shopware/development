<?php

declare(strict_types=1);

namespace Shopware\Development;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin\KernelPluginLoader\KernelPluginLoader;

class Kernel extends \Shopware\Core\Kernel
{
    public function __construct(
        string $environment,
        bool $debug,
        KernelPluginLoader $pluginLoader,
        string $cacheId,
        ?string $version = null,
        ?Connection $connection = null
    ) {
        parent::__construct($environment, $debug, $pluginLoader, $cacheId, $version);

        self::$connection = $connection;
        if (!$connection) {
            parent::getConnection();
        }
    }
}
