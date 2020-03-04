#DESCRIPTION: Install dependencies and prepare database for Cypress usage

npm install --prefix vendor/shopware/platform/src/"__CYPRESS_ENV__"/Resources/app/"$(echo "__CYPRESS_ENV__" | tr '[:upper:]' '[:lower:]')"/test/e2e
./psh.phar init-test-databases
./psh.phar e2e:dump-db
