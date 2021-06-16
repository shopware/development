#!/usr/bin/env bash
#DESCRIPTION: Run static code analysis on core

if grep -q static-analyze platform/composer.json; then bash ./dev-ops/common/scripts/static-analyze.sh; else bash ./dev-ops/common/scripts/old-static-analyze.sh; fi
