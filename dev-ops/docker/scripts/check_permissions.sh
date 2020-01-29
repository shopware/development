#!/usr/bin/env bash

# create directories if they don't exists with user privileges.
# otherwise docker might create them with root privileges

DIRS="$HOME/.composer"
DIRS="$DIRS $HOME/.npm"
DIRS="$DIRS $PWD/vendor/shopware/platform/src/Administration/Resources/app/administration/test/e2e"
DIRS="$DIRS $PWD/vendor/shopware/platform/src/Storefront/Resources/app/storefront/test/e2e"

for dir in $DIRS; do
    mkdir -p $dir || true
done

if [[ "$OSTYPE" == "darwin"* ]]; then
    for dir in $DIRS; do
        (cd "$dir") || {
          echo "$dir is not accessible"
          exit 1
        }

        if [[ $(stat -f '%Su' "$dir") == 'root' ]]; then
            err_msg="Error: The owner of $dir is root. This can cause problems with your docker setup. Please change the owner/group of these folders."
            echo $err_msg;
            exit 1
        fi
    done
elif [[ "$OSTYPE" == "linux"* ]]; then
    for dir in $DIRS; do
        (cd "$dir") || {
          echo "$dir is not accessible"
          exit 1
        }

        if [[ $(stat -c '%U' "$dir") == 'root' ]]; then
            err_msg="Error: The owner of $dir is root. This can cause problems with your docker setup. Please change the owner/group of these folders."
            echo $err_msg;
            exit 1
        fi
    done
fi
