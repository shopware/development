#!/usr/bin/env bash

bin/console docs:dump-er
bin/console docs:dump-core-characteristics
bin/console docs:dump-platform-updates

docker build -t shopware-plattform-plantuml dev-ops/docs/docker/plantuml/.
sh ./dev-ops/docs/scripts/render_puml.sh __DOCS_DIR__

bin/console docs:convert -i platform/src/Docs/Resources/current/ -o build/docs -u /shopware-platform-dev
