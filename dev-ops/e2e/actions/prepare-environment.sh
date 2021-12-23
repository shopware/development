#DESCRIPTION: Install dependencies and prepare database for Cypress usage

npm install --prefix platform/tests/e2e
./psh.phar init-test-databases
./psh.phar e2e:dump-db

if [ "__CYPRESS_LOCAL__" != "1" ]; then forever start platform/tests/e2e/node_modules/@shopware-ag/e2e-testsuite-platform/routes/cypress.js;  fi
