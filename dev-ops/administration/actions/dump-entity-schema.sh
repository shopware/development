#!/usr/bin/env bash
#DESCRIPTION: dumps the entity schema to the test folder in the administration

bin/console framework:schema -s 'entity-schema' __PROJECT_ROOT__/vendor/shopware/platform/src/Administration/Resources/app/administration/test/_mocks_/entity-schema.json
