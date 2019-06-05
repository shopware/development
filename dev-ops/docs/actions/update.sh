#!/usr/bin/env bash
# Description: Updates all generated markdown files

bin/console docs:dump-er
bin/console docs:dump-core-characteristics
bin/console docs:dump-platform-updates
