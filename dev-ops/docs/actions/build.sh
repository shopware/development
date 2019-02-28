#!/usr/bin/env bash

docker build -t shopware-plattform-plantuml dev-ops/docs/docker/plantuml/.

sh ./dev-ops/docs/scripts/render_puml.sh __DOCS_DIR__
