<?php

namespace Shopware\Development;

class Kernel extends \Shopware\Kernel
{
    public function __construct(string $environment, bool $debug, \PDO $connection = null)
    {
        parent::__construct($environment, $debug);

        if (!$connection) {
            $connection = parent::getConnection();
        }

        self::$connection = $connection;
    }
}