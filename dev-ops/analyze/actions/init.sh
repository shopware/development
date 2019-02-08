#!/usr/bin/env bash
#DESCRIPTION: Initialize dependencies deferred

cd dev-ops/analyze && composer install
D: cd dev-ops/analyze && npm clean-install
