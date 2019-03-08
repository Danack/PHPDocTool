
set -e
set -x


ENV_TO_USE=${ENV_DESCRIPTION:=default}

echo "ENV_TO_USE is ${ENV_TO_USE}";


# Generate nginx config file for the centos,dev environment
php vendor/bin/configurate \
    -p server_config.php \
    docker/php_fpm/config/default.vcl.php \
    docker/varnish/config/default.vcl \
    $ENV_TO_USE


sh /usr/sbin/php-fpm7.2 \
    --nodaemonize \
    --fpm-config=/var/app/docker/php_fpm/config/fpm.conf \
     -c /var/app/docker/php_fpm/config/php.ini