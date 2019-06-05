#!/usr/bin/env bash

set -eu

readonly DOCS_DIR=$1

for file in $(find $DOCS_DIR -name '*.puml' -print); do
    DEST_NAME=$(basename -s .puml $file)
    DEST_DIR=$(dirname $(dirname $file))/dist
    DEST_SVG_PATH=${DEST_DIR}/${DEST_NAME}.svg
    DEST_PNG_PATH=${DEST_DIR}/${DEST_NAME}.png

    echo "RENDERING $file to "
    mkdir -p $DEST_DIR

#    echo "\t${DEST_SVG_PATH}"
#    echo $(cat ${file} | docker run -i --rm shopware-plattform-plantuml -tsvg > ${DEST_SVG_PATH})

    echo "\t${DEST_PNG_PATH}"
    echo $(cat ${file} | docker run -i --rm shopware-plattform-plantuml -tpng > ${DEST_PNG_PATH})
done
