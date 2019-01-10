#!/usr/bin/env bash

# create directories if they don't exists with user privileges.
# otherwise docker might create them with root privileges

composer="$HOME/.composer"
npm="$HOME/.npm"

mkdir -p $composer
mkdir -p $npm

err_msg="Error: The owner of $composer and/or $npm is root. This can cause problems with your docker setup.
Please change the owner/group of these folders."


if [[ "$OSTYPE" == "darwin"* && $(stat -f '%Su' "$composer") != 'root' && $(stat -f '%Su' "$npm") != 'root' ]]; then
    exit
elif [[ "$OSTYPE" == "linux-gnu"* && $(stat -c '%U' "$composer") != 'root' && $(stat -c '%U' "$npm") != 'root'  ]]; then
    exit
fi;

echo "$err_msg";
exit 1