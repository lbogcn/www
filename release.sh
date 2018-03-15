#!/bin/sh

cd $(cd $(dirname $0); pwd)

chmod -R 777 ./storage
chmod -R 777 ./bootstrap/cache

/usr/local/php/bin/php artisan route:cache
/usr/local/php/bin/php artisan config:cache
/usr/local/php/bin/php artisan optimize --force

chown -R www:www $(cd `dirname $0`; pwd)
