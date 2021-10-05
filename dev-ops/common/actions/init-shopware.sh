#!/usr/bin/env bash
#DESCRIPTION: initialization of shopware

INCLUDE: ./cache.sh

# These files are not required anymore after a reinstall
rm -rf public/bundles/*
rm -rf public/media/*
rm -rf public/sitemap/*
rm -rf public/theme/*
rm -rf public/thumbnail/*
rm -rf files/export/*
rm -rf files/media/*
rm -rf var/log/*

SHOPWARE_INSTALL=1 bin/console system:install --basic-setup --force
