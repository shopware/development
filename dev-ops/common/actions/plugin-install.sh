#!/usr/bin/env bash

if [ -z "__NAME__" ]; then echo "The name of plugin is required. Please using --name to specify it."; exit 1; fi
if [ ! -e "./custom/plugins/__NAME__" ]; then echo "No plugins found [__NAME__] "; exit 1;fi
composer require `cat ./custom/plugins/__NAME__/composer.json | sed 's/\\\\\//\//g' | sed 's/[{}]//g' | awk -v k="text" '{n=split($0,a,","); for (i=1; i<=n; i++) print a[i]}' | sed 's/\"\:\"/\|/g' | sed 's/[\,]/ /g' | sed 's/\"//g' | grep -w name | head -n 1 | sed 's/name: //g'`
bin/console plugin:refresh
bin/console plugin:install __NAME__
