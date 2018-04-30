#!/bin/bash

USER="application"

if [ "$1" -ne "1000" ]; then
    USER="git-user"
    adduser --system --uid "$1" git-user
fi

mkdir /home/$USER/.ssh
touch /home/$USER/.ssh/id_rsa
touch /home/$USER/.ssh/known_hosts
chown -R $USER /home/$USER/.ssh
chmod 400 /home/$USER/.ssh/id_rsa

ssh-keyscan stash.shopware.com >> /home/$USER/.ssh/known_hosts