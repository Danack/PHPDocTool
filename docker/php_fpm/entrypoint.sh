
set -e
set -x


ENV_TO_USE=${ENV_DESCRIPTION:=default}

echo "ENV_TO_USE is ${ENV_TO_USE}";


# Generate nginx config file for the centos,dev environment
php vendor/bin/configurate \
    -p server_config.php \
    docker/varnish/config/default.vcl.php \
    docker/varnish/config/default.vcl \
    $ENV_TO_USE

sh /var/app/docker/varnish/start_varnish.sh
