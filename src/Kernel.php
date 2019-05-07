<?php declare(strict_types=1);

namespace Shopware\Development;

use Composer\Autoload\ClassLoader;
use Doctrine\DBAL\Connection;

class Kernel extends \Shopware\Core\Kernel
{
    public function __construct(string $environment, bool $debug, ClassLoader $classLoader, ?string $version = null, ?Connection $connection = null)
    {
        parent::__construct($environment, $debug, $classLoader, $version);

        self::$connection = $connection;
        if (!$connection) {
            parent::getConnection();
        }
    }
}
