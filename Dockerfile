FROM php:8.3.20-apache

# Enable GD extension
RUN apt-get update
RUN apt-get install --yes --force-yes curl cron g++ gettext libicu-dev openssl libc-client-dev libkrb5-dev libxml2-dev libfreetype6-dev libgd-dev libmcrypt-dev bzip2 libbz2-dev libtidy-dev libcurl4-openssl-dev libz-dev libmemcached-dev libxslt-dev

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli 
RUN docker-php-ext-enable mysqli

RUN docker-php-ext-configure gd --with-freetype=/usr --with-jpeg=/usr
RUN docker-php-ext-install gd

# Install codeigniter dependencies
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Download the composer
RUN curl -sS --insecure https://getcomposer.org/installer -o composer-setup.php

# Install the composer
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Install mysqli extension
RUN docker-php-ext-install mysqli

# ENABLE MOD REWRITE
RUN a2enmod rewrite

# Set working directory inside the container
WORKDIR /var/www/html

# Copy everything from the current folder (app/) to /var/www/html/
COPY . .

# COPY default HTTP CONF FILE
COPY 000-default.conf /etc/apache2/sites-available/

# Give correct permissions and ownership to the CONTENTS of writable/
RUN chown -R www-data:www-data /var/www/html/createmore_admin/writable && \
    find /var/www/html/createmore_admin/writable -type d -exec chmod 775 {} \; && \
    find /var/www/html/createmore_admin/writable -type f -exec chmod 664 {} \;

# Copy php.ini-production to php.ini
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Modify upload_max_filesize and post_max_size in php.ini
RUN sed -i 's/upload_max_filesize = .*/upload_max_filesize = 20M/' /usr/local/etc/php/php.ini \
    && sed -i 's/post_max_size = .*/post_max_size = 50M/' /usr/local/etc/php/php.ini

# Install nano editor
RUN apt-get clean && \
    apt-get update && \
    apt-get install -y nano && \
    rm -rf /var/lib/apt/lists/*

# Restart Apache
RUN service apache2 restart

