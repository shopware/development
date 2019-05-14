#!/usr/bin/env sh

#DESCRIPTION: runs e2e tests with cypress for the administration
INCLUDE: ./../../common/actions/cache.sh

bin/console administration:dump:features

INCLUDE: ./../actions/dump-db.sh
