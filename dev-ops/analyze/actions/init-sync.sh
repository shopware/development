#!/usr/bin/env bash
#DESCRIPTION: Initialize dependencies synchronous

cd dev-ops/analyze && composer install
cd dev-ops/analyze && npm clean-install
