#DESCRIPTION: Install dependencies and prepare database for Cypress usage

npm install --prefix vendor/shopware/platform/src/"__CYPRESS_ENV__"/Resources/app/"$(echo "__CYPRESS_ENV__" | tr '[:upper:]' '[:lower:]')"/test/e2e
./psh.phar init-test-databases
./psh.phar e2e:dump-db

if [ "__CYPRESS_LOCAL__" != "1" ]; then forever start vendor/shopware/platform/src/Administration/Resources/app/administration/test/e2e/node_modules/@shopware-ag/e2e-testsuite-platform/routes/cypress.js;  fi
