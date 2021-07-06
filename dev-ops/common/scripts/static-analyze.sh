#DESCRIPTION: Static analyze for current platform

# we've moved the static analyze tools into platform
cd platform

# run composer install if it's not initialized yet
if [[ ! -r vendor/autoload.php ]]; then
   composer update
fi

composer run static-analyze
