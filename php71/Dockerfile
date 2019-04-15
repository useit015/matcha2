FROM php:7.1-apache

# Enable PDO and mysqli (for phpmyadmin)
RUN docker-php-ext-install pdo_mysql mysqli 

# Enable intl
RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++
RUN docker-php-ext-install intl

# Install wget
RUN  apt-get update && apt-get install -y wget && rm -rf /var/lib/apt/lists/*

# Install php-soap
RUN rm /etc/apt/preferences.d/no-debian-php
RUN apt-get update -y && apt-get install -y libxml2-dev php-soap && apt-get clean -y
RUN docker-php-ext-install soap

# Enable imagick
RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN yes '' | pecl install imagick-beta

# Install mcrypt
RUN apt-get update -y && apt-get install -y libmcrypt-dev
RUN docker-php-ext-install mcrypt

# Install composer
ADD composer-install.sh /usr/local/bin/composer-install.sh
RUN /usr/local/bin/composer-install.sh && mv composer.phar /usr/bin/composer

# Install Git zip & unzip
RUN apt-get update && apt-get install -y git zip unzip

# Setup system configurations
COPY php.ini /usr/local/etc/php/
COPY vhost.conf /etc/apache2/sites-enabled/
RUN rm /etc/apache2/sites-enabled/000-default.conf

# Enable rewrite module
RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load

# Ensure www-data has necessary permission
RUN usermod -u 1000 www-data

COPY bootstrap.sh /usr/local/bin/bootstrap.sh
CMD ["bootstrap.sh"]

# MAIL configuration
RUN apt-get update && \
   apt-get install -y ssmtp && \
   apt-get clean

# SSMTP settings
COPY ssmtp.conf /etc/ssmtp/ssmtp.conf
# PHP mail settings
RUN echo 'sendmail_path = "/usr/sbin/ssmtp -t -i"' > /usr/local/etc/php/conf.d/mail.ini
RUN echo 'root:ousstest015@gmail.com:smtp.gmail.com:587\nshitmail:ousstest015@gmail.com:smtp.gmail.com:587' > /etc/ssmtp/revaliases

# Apache configuration
RUN apt install -y vim
RUN echo '<VirtualHost *:80>\n  DocumentRoot /docker-pamp/src\n  <Directory /docker-pamp/src>\n      AllowOverride All\n      Require all granted\n      Allow from all\n    <IfModule mod_rewite.c>\n      Options -Multiviews\n      RewriteEngine On\n      RewriteCond %{REQUEST_FILENAME} !-f\n      RewriteRule ^(.*)$ index.php [QSA,L]\n    </IfModule>\n  </Directory>\n  ErrorLog /var/log/apache2/docker_pamp_error.log\n  CustomLog /var/log/apache2/docker_pamp_access.log combined\n</VirtualHost>\n' > /etc/apache2/sites-enabled/vhost.conf
RUN a2enmod headers
RUN service apache2 restart

# Install php-gd
RUN apt update \
   && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
   && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
   && docker-php-ext-install -j$(nproc) intl pdo_mysql bcmath mbstring exif gd
