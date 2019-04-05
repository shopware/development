#!/usr/bin/env bash

docker build -t shopware-plattform-plantuml dev-ops/docs/docker/plantuml/.

sh ./dev-ops/docs/scripts/render_puml.sh __DOCS_DIR__

#./psh.phar docker:start

#docker exec -u __USERKEY__ __APP_ID__ ./psh.phar init
#docker exec -u __USERKEY__ __APP_ID__ bin/console -p framework:schema platform/src/Docs/_new/3-api/dist/management-api.json
