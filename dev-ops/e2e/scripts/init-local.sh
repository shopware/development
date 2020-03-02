#!/usr/bin/env bash

# Prepare Shopware
./psh.phar e2e:prepare-shopware

# Prepare database dump
./psh.phar e2e:prepare-environment;
