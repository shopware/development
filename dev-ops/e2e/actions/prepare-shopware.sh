#DESCRIPTION: Prepare shopware installation for Cypress usage

php dev-ops/generate_ssl.php
./psh.phar init-composer
./psh.phar init-database
./psh.phar init-shopware
./psh.phar administration:build
./psh.phar storefront:build
