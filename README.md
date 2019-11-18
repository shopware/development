# Shopware 6 development template

This repository is a template for local development and enables you to create a running Shopware 6 instance.
Use this setup for developing directly on Shopware 6 or for developing plugins for Shopware 6.

The installation guide, together with the complete documentation, is available at [docs.shopware.com](https://docs.shopware.com/en/shopware-platform-dev-en/getting-started).

# Installation

# Installation

## Setup for Windows
The windows setup requires the WSL (Windows Subsystems for Linux). After you activated the windows feature (WSL) you need to install a sub-system. Simply open the microsoft store and search for e.g. "Ubuntu". Once the setup is complete, you need to install the docker client and php:

1. For Docker follow this guide:
    - https://nickjanetakis.com/blog/setting-up-docker-for-windows-and-wsl-to-work-flawlessly
    - Install Docker-Compose
2. Install PHP 7.2
    - apt-get install php

After that you should be able to setup Shopware like below described.

## Setup Docker
You can almost follow the docker setup from the official documentation: https://docs.shopware.com/en/shopware-platform-dev-en/getting-started/installation-guide#preparation. Just note the following two changes.
1. Replace the repository from the first git clone command with this one here.
	-  ~~git clone git@github.com:shopware/development.git~~ 
	- git clone git@github.com:buenosdiaz/shopware6-development 
2. After `./psh.phar docker:start` run also the command `./psh.phar docker:init-data`

Thats it.

## Local SFTP Deployment

In order to avoid performance issues we use a data container instead of local volumes, so that we need to create a sftp deployment for our local changes. Use the following SFTP credentials for a SFTP auto upload in PHPStorm e.g.:


```c
host:127.0.0.1
port:2222
user:root
password:nexus123
```
