#!/usr/bin/env bash
#DESCRIPTION: clears all caches

if [ -d "var/cache" ]; then find var/cache -maxdepth 1 ! -name cs_fixer ! -name phpstan ! -name psalm ! -wholename var/cache -exec rm -rf {} \;; fi
