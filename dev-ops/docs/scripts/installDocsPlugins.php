<?php

$list = require __DIR__ . '/../plugins.php';
$rootDir = __DIR__ . '/../../../';
$result = [];

foreach($list as $name) {
    $retVal = false;

    echo "bin/console plugin:uninstall $name\n";
    system("cd $rootDir && bin/console plugin:uninstall $name");
    echo "bin/console plugin:install $name\n";
    system("cd $rootDir && bin/console plugin:install --activate $name", $retVal);

    $result[$name] = $retVal;
}

print_r($result);
