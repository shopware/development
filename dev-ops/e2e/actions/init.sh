#DESCRIPTION: Prepares Shopware installation and environment for Cypress usage

if [ "__CYPRESS_LOCAL__" = "1" ]; then bash ./dev-ops/e2e/scripts/init-local.sh;  fi
if [ "__CYPRESS_LOCAL__" != "1" ]; then bash ./dev-ops/e2e/scripts/init-docker.sh __USERKEY__ $(docker-compose ps -q app_server); fi
