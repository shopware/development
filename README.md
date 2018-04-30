# Shopware development setup

## Installation

* Clone sources `git clone git@github.com:shopware/development.git`
* Move into directory `cd development`
* Execute setup file `bin/setup`
* Follow instructions, following explanation is based on the default values
    * Host: `http://shopware.local`
    * tenant id: `ffffffffffffffffffffffffffffffff`


## Setup host and environment

host configuration: `/etc/hosts` 
```
127.0.0.1 shopware.local
```

Apache vhost configuration: `/etc/apache2/extra/httpd-vhosts.conf`

```
<VirtualHost *:80>
    DocumentRoot "/path/to/development/public"
    ServerName shopware.local
    SetEnv TENANT_ID ffffffffffffffffffffffffffffffff
</VirtualHost>
```

`SetEnv TENANT_ID` is required for tenant support. Can also be provided as http request header `x-sw-tenant-id`
Without tenant id, the request will be rejected.


## Using custom images for demo data

The demo data generator runs after each init command. By default, there will simple images be generated. If you want to use your own images, create a folder `./build/media` and put your images into this folder. The demo data generator will now look for images in this folder and use them for the products.

## Development

* The development project contains an entrypoint to the system.
* `vendor/shopware/platform` is the mono repository containing all required components to run Shopware including:
    * **SORM** - Shopware ORM
    * Rest API
    * Storefront API
    * Administration
    * Documentation

## Configure PHPStorm

* Open directory with your PHPStorm
* Add `vendor/shopware/platform` as source directory
    * `settings` > `directories` > **select directories** > **top-bar button : sources**
* Add `vendor/shopware/platform` directory to VCS
    * `settings` > `Version Controller` > **bottom bar : "+" button** > **select directories**

*Have fun - your core development*  
