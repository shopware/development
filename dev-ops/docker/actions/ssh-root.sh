#!/usr/bin/env bash

TTY: docker exec -i --env COLUMNS=`tput cols` --env LINES=`tput lines` -u 0 -t __APP_ID__ bash
