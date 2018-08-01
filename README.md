# Shopware development template

This repository is a template for local development. It enables you to create a running Shopware instance to test new technologies from shopware, including the new *core* or the new *administration*.

## Docker setup

* Make sure your ~/.composer folder is writable by your user (it often belongs to root), if the folder exists
* Clone the repository: `git clone git@github.com:shopware/development.git`
* Move into the directory: `cd development`
* Start the docker container: `./psh.phar docker:start`
* Connect to the container: `./psh.phar docker:ssh` *(all future commands are now running inside of the docker container)*
    * Run the installation process: `./psh.phar install`
* Open [http://localhost:8000](http://localhost:8000) or [http://localhost:8000/admin](http://localhost:8000/admin)

To shutdown the container, logout from the container and run stop:

```
./psh.phar docker:stop
```

## Local setup

### System requirements

* PHP 7.2
* MySQL 5.7
* Composer 1.6

### Installation steps

* Clone the repository: `git clone git@github.com:shopware/development.git`
* Move into the directory: `cd development`
* Execute the setup file: `bin/setup`
* Follow the instructions provided by the installation wizard. The following settings will be used by default:
    * Host: `http://shopware.local`
    * tenant id: `43210f5d06f6411cba1794db3f834477`

### Setup host and environment

Add the settings for your configured host: `/etc/hosts` 
```
127.0.0.1 shopware.local
```

Also add the information to your apache `vhost` configuration and set the path to the `/public` directory of your project as the document root: `/etc/apache2/extra/httpd-vhosts.conf`

```
<VirtualHost *:80>
    DocumentRoot "/path/to/your/project/public"
    ServerName shopware.local
    SetEnv TENANT_ID 43210f5d06f6411cba1794db3f834477
</VirtualHost>
```
**Important:** You will also have to set the environment variable `TENANT_ID` to your configured default tenant id. Without this configuration all requests will be rejected. The id can also be provided as the http request header `x-sw-tenant-id`.


## Helpful console commands

You can do some important tasks by running different commands using the `psh.phar` file in the root directory. If you just call the file without parameters you will get a complete overview of all commands. Here are some common commands:

* `./psh.phar install`: Resets the instance. This includes updating the composer dependencies, reinstall the database, install assets, import demo data and initializing the administration
* `./psh.phar init`: Resets the database and updates the composer dependencies.
* `./psh.phar cache`: Clears the cache.
* `./psh.phar demo-data`: Generates a set of demo data.
* `./psh.phar administration:init`: Initializes the administration and installs the required npm dependencies.
* `./psh.phar administration:watch`: Starts a local server for developing with the administration, including hot reloading and live linting.


## Using custom images for demo data

The demo data generator runs after each init command. By default, the generator will create simple auto generated images. If you want to use your own images, create the folder `./build/media` and put your images into this folder. The demo data generator will now look for images in this folder and use them for the products.

## Development and contributing

This repository is just a template for local development and does not represent a real product of shopware. On installation it will require the mono repository **shopware/platform** which contains a library of the new shopware technologies. So the platform repository should be your entry point for making changes and committing new code. After installation you will find it in `vendor/shopware/platform`. You can configure your IDE to use this directory as your main directory and also to use it for GIT version control. For more information about the different components of the platform repository and on how to contribute, you can visit the page of the repository.

## Configure PHPStorm
* Open the project with your PHPStorm
* Add `vendor/shopware/platform` as your source directory
    * `settings` > `directories` > **select directory** > **Mark as: Sources**
* Add `vendor/shopware/platform` directory to your version control
    * `settings` > `Version Control` > **"+"** > **select directory**

*Have fun - your core development*  
