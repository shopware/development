<?php declare(strict_types=1);

namespace Shopware\Development;

use Doctrine\DBAL\Connection;

class Kernel extends \Shopware\Core\Kernel
{
    public function __construct(string $environment, bool $debug, ?Connection $connection = null)
    {
        parent::__construct($environment, $debug);

        self::$connection = $connection;
        if (!$connection) {
            parent::getConnection();
        }
    }
}
