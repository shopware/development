#DESCRIPTION: Installs Cypress' dependencies and prepares Shopware installation

if [ "__CYPRESS_LOCAL__" = "1" ]; then bash ./dev-ops/e2e/scripts/init-local.sh;  fi

if [ -z "__CYPRESS_LOCAL__" ]; then bash ./dev-ops/e2e/scripts/init-docker.sh  __USERKEY__ __APP_ID__ __CYPRESS_ENV__; fi
