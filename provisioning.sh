#!/usr/bin/env bash

apt-get update
apt-get upgrade -y

apt-get install -y \
			software-properties-common \
			nginx \
			php \
			php-memcached \
			php-xml \
			php-curl \
			php-mysql \
			php-mbstring \
			php-msgpack \
			php-xdebug \
			zip \
			unzip \
			git \
			ssl-cert \
			mysql-server \
			redis-server \
			default-jre \
			default-jdk

cat << 'VHOST' > /etc/nginx/sites-available/interns.conf
server {
        listen 80;
        listen 443 ssl;

        include snippets/snakeoil.conf;

        root /vagrant;

        # Add index.php to the list if you are using PHP
        index index.php;

        server_name interns.local;

        location / {
                # First attempt to serve request as file, then
                # as directory, then fall back to displaying a 404.
                try_files $uri $uri/ /index.php?$query_string;
        }

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
        #
        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php7.0-fpm.sock;
        }
}
VHOST

ln -s /etc/nginx/sites-available/interns.conf /etc/nginx/sites-enabled/interns.conf