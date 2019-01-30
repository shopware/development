#!/usr/bin/env bash
#DESCRIPTION: worker script for handling async messages

APP_ENV=prod bin/console messenger:consume-messages __TRANSPORT__