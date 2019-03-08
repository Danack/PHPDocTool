FROM debian:9

USER root

#RUN apt-get update -qq \
#    && DEBIAN_FRONTEND=noninteractive apt-get install -y nginx \
#    ca-certificates



# Get Debian up-to-date
RUN apt-get update -qq \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y nginx git \
    mariadb-client wget curl \
    ca-certificates lsb-release apt-transport-https gnupg bsdmainutils

# Install 3rd party PHP 7.2 packages
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee -a /etc/apt/sources.list.d/php.list
RUN curl -L https://packages.sury.org/php/apt.gpg | apt-key add -

RUN  apt-get update -qq \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y php7.2 php7.2-common php7.2-cli php7.2-fpm


WORKDIR /var/app

# CMD ["/usr/sbin/nginx", "-c", "/var/app/docker/nginx/config/nginx.conf"]

CMD sh /var/app/docker/nginx/entrypoint.sh



# For debugging, rather than having the container exit immediately,
# being able to switch the command to be just 'follow that file' allows
# you to bash into the container to inspect the state after nginx has crashed.
# CMD ["tail", "-f", "/var/app/readme.MD"]