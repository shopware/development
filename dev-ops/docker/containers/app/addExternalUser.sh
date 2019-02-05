#!/bin/bash

USER="application"

if [ "$1" -ne "1000" ]; then
    USER="git-user"
    adduser --system --uid "$1" git-user
fi
