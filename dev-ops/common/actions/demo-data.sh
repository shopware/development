#!/usr/bin/env bash
#DESCRIPTION: creates a demo data set

bin/console framework:demodata --products=500 --categories=5 --manufacturers=25 -eprod --tenant-id=__TENANT_ID__
bin/console dbal:refresh:index -eprod --tenant-id=__TENANT_ID__
