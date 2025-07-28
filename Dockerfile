FROM php:8.3.20-apache

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl cron g++ gettext libicu-dev openssl \
    libc-client-dev libkrb5-dev libxml2-dev \
    libfreetype6-dev libgd-dev libmcrypt-dev \
    bzip2 libbz2-dev libtidy-dev libcurl4-openssl-dev \
    libz-dev libmemcached-dev libxslt-dev \
    nano && \
    rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install mysqli intl \
    && docker-php-ext-enable mysqli

# Configure GD with freetype and jpeg
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Set working directory
WORKDIR /var/www/html

# Replace Apache default site configuration
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Copy and modify php.ini
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
    sed -i 's/upload_max_filesize = .*/upload_max_filesize = 20M/' /usr/local/etc/php/php.ini && \
    sed -i 's/post_max_size = .*/post_max_size = 50M/' /usr/local/etc/php/php.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php
