<?php

$list = require __DIR__ . '/../plugins.php';
$pluginsDestinationDir = __DIR__ . '/../../../custom/plugins';

const REPO_SOURCE = 'git@github.com:shopware/%s.git';

foreach($list as $dirName => $name) {
    $repo = sprintf(REPO_SOURCE, $dirName);

    if(!is_dir($pluginsDestinationDir . "/$dirName")) {
        system("cd $pluginsDestinationDir && git clone $repo $dirName");
    } else {
        system("cd $pluginsDestinationDir/$dirName && git remote -v");
        system("cd $pluginsDestinationDir/$dirName && git checkout master");
        system("cd $pluginsDestinationDir/$dirName && git reset HEAD --hard ");
        system("cd $pluginsDestinationDir/$dirName && git clean -fdx");
    }
}
