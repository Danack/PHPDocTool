<?php


function doctool_server_block($domain_name, string $http_listen, $php_fpm_connection)
{
    $static_files_block = <<< END
      try_files \$uri /index.php?file=$1.$2&q=\$uri&\$args;
      expires 20m;
      add_header Pragma public;
      add_header Cache-Control "public, no-transform, max-age=1200, s-maxage=300";
END;

    $doctool_server_block = <<< END
  
  server {
    server_name local.$domain_name *.$domain_name $domain_name;
    
    $http_listen
    root /var/app/app/public;

    location ~* ^/doctool/(.+).(bmp|bz2|css|gif|doc|gz|html|ico|jpg|jpeg|js|mid|midi|png|rtf|rar|pdf|ppt|svg|tar|tgz|ttf|txt|wav|woff|xls|zip)$ {
      $static_files_block
    }

    location ~* ^(.+).(bmp|bz2|css|gif|doc|gz|html|ico|jpg|jpeg|js|mid|midi|png|rtf|rar|pdf|ppt|svg|tar|tgz|ttf|txt|wav|woff|xls|zip)$ {
      root /var/app/php.net;
      $static_files_block
    }

    # this has highest priority
    # route to doctool router
    location = /doctool_index.php {
      try_files \$uri /doctool_index.php?q=\$uri&\$args =404;
      # Mitigate https://httpoxy.org/ vulnerabilities
      fastcgi_param HTTP_PROXY "";
      include /var/app/docker/nginx/config/fastcgi.conf;
      fastcgi_param SCRIPT_FILENAME \$document_root/\$fastcgi_script_name;
      fastcgi_read_timeout 300;
      fastcgi_pass $php_fpm_connection;
    }
    
    # this has 2nd highest priority
    # route to php.net router
    location = /index.php {
      root /var/app/php.net;
      try_files \$uri /index.php?q=\$uri&\$args =404;
      # Mitigate https://httpoxy.org/ vulnerabilities
      fastcgi_param HTTP_PROXY "";
      include /var/app/docker/nginx/config/fastcgi.conf;
      fastcgi_param SCRIPT_FILENAME \$document_root/\$fastcgi_script_name;
      fastcgi_read_timeout 300;
      fastcgi_pass $php_fpm_connection;
    }
    
    
    location ~* ^/doctool(.*) {
      # root /var/app/app/public;
      try_files \$uri /doctool_index.php?q=\$uri&\$args;
      # Mitigate https://httpoxy.org/ vulnerabilities
      fastcgi_param HTTP_PROXY "";
      include /var/app/docker/nginx/config/fastcgi.conf;
      fastcgi_param SCRIPT_FILENAME \$document_root/\$fastcgi_script_name;
      fastcgi_read_timeout 300;
      fastcgi_pass $php_fpm_connection;
    }

    location ~* \.php$ {
      root /var/app/php.net;
      try_files \$uri /index.php?q=\$uri&\$args =404;
      # Mitigate https://httpoxy.org/ vulnerabilities
      fastcgi_param HTTP_PROXY "";
      include /var/app/docker/nginx/config/fastcgi.conf;
      fastcgi_param SCRIPT_FILENAME \$document_root/\$fastcgi_script_name;
      fastcgi_read_timeout 300;
      fastcgi_pass $php_fpm_connection;
    }

    location = / {
      try_files \$uri \$uri/index.php /doctool_index.php;
    }
    
    location / {
      root /var/app/php.net;
      try_files \$uri \$uri.php \$uri/index.php;
      # Mitigate https://httpoxy.org/ vulnerabilities
      fastcgi_param HTTP_PROXY "";
      # fastcgi_index index.php;
      include /var/app/docker/nginx/config/fastcgi.conf;
      fastcgi_param SCRIPT_FILENAME \$document_root/\$fastcgi_script_name;
      fastcgi_read_timeout 300;
      fastcgi_pass $php_fpm_connection;
    }
  }
END;

    return $doctool_server_block;
}


$doctool_server_block_normal = doctool_server_block(
    $domain_name,
    "listen 80;\n    listen 8000;\n",
    "php_fpm:9000"
);


$doctool_server_block_debug = doctool_server_block(
    $domain_name,
    "listen 8001;\n",
    "php_fpm_debug:9000"
);




$config = <<< END

user www-data;
worker_processes auto;
pid /run/nginx.pid;
#include /etc/nginx/modules-enabled/*.conf;
daemon off;

events {
    worker_connections 768;
    # multi_accept on;
}

http {
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    types_hash_max_size 2048;
    client_max_body_size 10m;  

    include /var/app/docker/nginx/config/mime.types;
    default_type application/octet-stream;

    access_log /dev/stdout;
    error_log /dev/stderr;

    gzip on;
    gzip_vary on;
    gzip_proxied any;

    #Set what content types may be gzipped.
    gzip_types text/plain text/css application/json application/javascript application/x-javascript text/javascript text/xml application/xml application/rss+xml application/atom+xml application/rdf+xml;

    server {
        listen 80 default_server;
        listen 8000 default_server;
        location / {
            return 404;
        }
    }

    # the normal block for the serving the doctool
    $doctool_server_block_normal

    # the debug block for the serving the doctool
    $doctool_server_block_debug
}

END;

return $config;


//
//
//# the normal block for the serving the manual
//server {
//    server_name local.php.net *.docs.phpimagick.com 'domain_name';
//        listen 80;
//        listen 8000;
//
//
//        location ~* ^(.+).(bmp|bz2|css|gif|doc|gz|html|ico|jpg|jpeg|js|mid|midi|png|rtf|rar|pdf|ppt|svg|tar|tgz|ttf|txt|wav|woff|xls|zip)$ {
//    ${manual_static_files_block}
//
//    }
//
//        location ~* \.php$ {
//    try_files $uri /index.php?q=$uri&$args;
//             # Mitigate https://httpoxy.org/ vulnerabilities
//             fastcgi_param HTTP_PROXY "";
//             include /var/app/docker/nginx/config/fastcgi.conf;
//             fastcgi_param SCRIPT_FILENAME $document_root/$fastcgi_script_name;
//             fastcgi_read_timeout 300;
//             fastcgi_pass php_fpm:9000;
//             proxy_hide_header X-Frame-Options;
//             add_header X-Frame-Options "allow-from http://local.doctool.phpimagick.com/";
//        }
//
//        location / {
//        try_files $uri $uri.php $uri/index.php;
//
//            # Mitigate https://httpoxy.org/ vulnerabilities
//            fastcgi_param HTTP_PROXY "";
//            # fastcgi_index index.php;
//            include /var/app/docker/nginx/config/fastcgi.conf;
//            fastcgi_param SCRIPT_FILENAME $document_root/$fastcgi_script_name;
//            fastcgi_read_timeout 300;
//            fastcgi_pass php_fpm:9000;
//            proxy_hide_header X-Frame-Options;
//            add_header X-Frame-Options "allow-from http://local.doctool.phpimagick.com/";
//        }
//
//        location /index.php {
//        # Mitigate https://httpoxy.org/ vulnerabilities
//        fastcgi_param HTTP_PROXY "";
//            # fastcgi_index index.php;
//            include /var/app/docker/nginx/config/fastcgi.conf;
//            fastcgi_param SCRIPT_FILENAME $document_root/$fastcgi_script_name;
//            fastcgi_read_timeout 300;
//            fastcgi_pass php_fpm:9000;
//            proxy_hide_header X-Frame-Options;
//            add_header X-Frame-Options "allow-from http://local.doctool.phpimagick.com/";
//        }
//    }
//
//    # the debug block for the serving the manual
//    server {
//    server_name local.php.net *.docs.phpimagick.com docs.phpimagick.com;
//        listen 8001;
//        root /var/app/php.net;
//
//        location ~* ^(.+).(bmp|bz2|css|gif|doc|gz|html|ico|jpg|jpeg|js|mid|midi|png|rtf|rar|pdf|ppt|svg|tar|tgz|ttf|txt|wav|woff|xls|zip)$ {
//        #access_log off;
//    try_files \$uri /index.php?file=$1.$2&q=\$uri&\$args;
//            expires 20m;
//            add_header Pragma public;
//            add_header Cache-Control "public, no-transform, max-age=1200, s-maxage=300";
//            proxy_hide_header X-Frame-Options;
//            add_header X-Frame-Options "allow-from http://local.doctool.phpimagick.com/";
//        }
//
//        location ~* \.php$ {
//    try_files \$uri /index.php?q=\$uri&\$args;
//             # Mitigate https://httpoxy.org/ vulnerabilities
//             fastcgi_param HTTP_PROXY "";
//             include /var/app/docker/nginx/config/fastcgi.conf;
//             fastcgi_param SCRIPT_FILENAME \$document_root/\$fastcgi_script_name;
//             fastcgi_read_timeout 300;
//             fastcgi_pass php_fpm_debug:9000;
//             proxy_hide_header X-Frame-Options;
//             add_header X-Frame-Options "allow-from http://local.doctool.phpimagick.com/";
//        }
//
//        location / {
//        try_files \$uri \$uri.php \$uri/index.php;
//
//            # Mitigate https://httpoxy.org/ vulnerabilities
//            fastcgi_param HTTP_PROXY "";
//            # fastcgi_index index.php;
//            include /var/app/docker/nginx/config/fastcgi.conf;
//            fastcgi_param SCRIPT_FILENAME \$document_root/\$fastcgi_script_name;
//            fastcgi_read_timeout 300;
//            fastcgi_pass php_fpm:9000;
//            proxy_hide_header X-Frame-Options;
//            add_header X-Frame-Options "allow-from http://local.doctool.phpimagick.com/";
//        }
//
//        location /index.php {
//        # Mitigate https://httpoxy.org/ vulnerabilities
//        fastcgi_param HTTP_PROXY "";
//            # fastcgi_index index.php;
//            include /var/app/docker/nginx/config/fastcgi.conf;
//            fastcgi_param SCRIPT_FILENAME \$document_root/\$fastcgi_script_name;
//            fastcgi_read_timeout 300;
//            fastcgi_pass php_fpm_debug:9000;
//            proxy_hide_header X-Frame-Options;
//            add_header X-Frame-Options "allow-from http://local.doctool.phpimagick.com/";
//        }
//    }
//
//
//





//
//
//# the debug block for the serving the doctool
//server {
//    server_name *.doctool.phpimagick.com doctool.phpimagick.com;
//        listen 8001;
//        root /var/app/app/public;
//
//        location ~* ^(.+).(bmp|bz2|css|gif|doc|gz|html|ico|jpg|jpeg|js|mid|midi|png|rtf|rar|pdf|ppt|tar|tgz|txt|wav|xls|zip)$ {
//        #access_log off;
//    try_files \$uri /index.php?file=$1.$2&q=\$uri&\$args;
//            expires 20m;
//            add_header Pragma public;
//            add_header Cache-Control "public, no-transform, max-age=1200, s-maxage=300";
//        }
//
//        location ~* \.php$ {
//    try_files \$uri /index.php?q=\$uri&\$args;
//             # Mitigate https://httpoxy.org/ vulnerabilities
//             fastcgi_param HTTP_PROXY "";
//             include /var/app/docker/nginx/config/fastcgi.conf;
//             fastcgi_param SCRIPT_FILENAME \$document_root/\$fastcgi_script_name;
//             fastcgi_read_timeout 300;
//             fastcgi_pass php_fpm_debug:9000;
//        }
//
//        location / {
//        try_files $uri $uri/index.php;
//        }
//
//        location /index.php {
//        # Mitigate https://httpoxy.org/ vulnerabilities
//        fastcgi_param HTTP_PROXY "";
//            # fastcgi_index index.php;
//            include /var/app/docker/nginx/config/fastcgi.conf;
//            fastcgi_param SCRIPT_FILENAME \$document_root/\$fastcgi_script_name;
//            fastcgi_read_timeout 300;
//            fastcgi_pass php_fpm_debug:9000;
//        }
//    }