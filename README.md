# Shopware SaaS setup

## Installation

* Clone sources `git clone ssh://git@stash.shopware.com/sw/saas.git`
* Move into directory `cd saas`
* Execute setup file `bin/setup`
* Follow instructions, following explanation is based on the default values
    * Host: `http://shopware.saas`
    * tenant id: `ffffffffffffffffffffffffffffffff`


## Setup host and environment

host configuration: `/etc/hosts` 
```
127.0.0.1 shopware.saas
```

vhosts configuration: `/etc/apache2/extra/httpd-vhosts.conf`

```
<VirtualHost *:80>
    DocumentRoot "~/Projects/saas/public"
    ServerName shopware.saas
    SetEnv TENANT_ID ffffffffffffffffffffffffffffffff
</VirtualHost>
```

`SetEnv TENANT_ID` is required for tenant support. Can also be provided as http request header `x-sw-tenant-id`
Without tenant id, the request will be rejected.

## Create a new tenant

It is already possible to create a new tenant in the saas setup with the following commands

* register new tenant in system (imports system data: taxes, languages, countries, ...)
    * ```bin/console framework:create:tenant --tenant-id=a0a3869830874686985f868d0ebe2482```
* import translations for tenant to allow modification
    * ```bin/console translation:import --with-plugins --tenant-id=a0a3869830874686985f868d0ebe2482```
* adds a rest api access and administration login
    * ```bin/console rest:user:create admin --password=shopware --tenant-id=a0a3869830874686985f868d0ebe2482```
* adds a shop storefront
    * ```bin/console application:create:storefront http://shopware.tenant --tenant-id=a0a3869830874686985f868d0ebe2482```
* add demo data
    * ```bin/console framework:demodata --products=500 --categories=5 --manufacturers=25 -eprod --tenant-id=a0a3869830874686985f868d0ebe2482```
* Add `/etc/apache2/extra/httpd-vhosts.conf` configuration:

```
<VirtualHost *:80>
    DocumentRoot "~/Projects/saas/public"
    ServerName shopware.saas
    SetEnv TENANT_ID ffffffffffffffffffffffffffffffff
</VirtualHost>
```

## Using custom images for demo data

The demo data generator runs after each init command. By default, there will simple images be generated. If you want to use your own images, create a folder `./build/media` and put your images into this folder. The demo data generator will now look for images in this folder and use them for the products.

## Development with SaaS

* Your root directory contains the specify sources to deploy shopware in a cloud system.
* `/vendor/shopware/platform` contains the following implementations:
    * **SORM** - Shopware ORM
    * Rest api
    * Storefront api
    * Administration
    * ... 
    * Repository: https://stash.shopware.com/projects/SW/repos/platform/browse
    
* `vendor/shopware/storefront` contains the shopware twig storefront sources:
    * Page loaders
    * Twig templates
    * CSS and JavaScript
    * Repository: https://stash.shopware.com/projects/SW/repos/storefront/browse

## Configure your PHP Storm

* Open directory with your php storm
* Add `vendor/shopware/storefront` and `/vendor/shopware/platform` as source directory
    * `settings` > `directories` > **select directories** > **top-bar button : sources**
* Mark `/vendor/shopware/platform/var` and `/vendor/shopware/platform/vendor` as excluded
    * `settings` > `directories` > **select directories** > **top-bar button : excluded**
* Add `vendor/shopware` directories to VCS
    * `settings` > `Version Controller` > **bottom bar : "+" button** > **select directories**

*Have fun - your core development*  