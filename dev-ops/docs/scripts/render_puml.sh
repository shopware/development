#!/usr/bin/env bash

set -eu

readonly DOCS_DIR=$1

for file in $(find $DOCS_DIR -name '*.puml' -print); do
    DEST_NAME=$(basename -s .puml $file)
    DEST_DIR=$(dirname $(dirname $file))/dist
    DEST_PATH=${DEST_DIR}/${DEST_NAME}.svg
    DEST_PNG_PATH=${DEST_DIR}/${DEST_NAME}.png

    echo "RENDERING $file to \n\t${DEST_PATH}\n\t${DEST_PNG_PATH}"
    mkdir -p $DEST_DIR
    echo $(cat ${file} | docker run -i --rm shopware-plattform-plantuml -tsvg > ${DEST_PATH})
    echo $(cat ${file} | docker run -i --rm shopware-plattform-plantuml -tpng > ${DEST_PNG_PATH})
done
