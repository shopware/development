#!/usr/bin/env bash
# Description: Generates all images and dumps converts the markdown files. Also deploys if this is possible

INCLUDE: ./build.sh
bin/console docs:convert -i platform/src/Docs/Resources/current/ -o build/docs -u /shopware-platform-dev -s
