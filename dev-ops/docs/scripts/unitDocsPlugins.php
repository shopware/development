<?php

$list = require __DIR__ . '/../plugins.php';
$rootDir = __DIR__ . '/../../../';

foreach($list as $dir => $name) {
    $retVal = false;

    echo "vendor/bin/phpunit -c custom/plugins/$dir/phpunit.xml.dist\n";
    system("cd $rootDir && vendor/bin/phpunit -c custom/plugins/$dir/phpunit.xml.dist", $retVal);

    if($retVal !== 0) {
        exit(1);
    }
}
