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

# COPY default HTTP CONF FILE
COPY 000-default.conf /etc/apache2/sites-available/


#RUN chown -R www-data:www-data /var/www/html/dobid/writable/uploads/forms/
#RUN chmod -R 775 /var/www/html/dobid/writable/uploads/forms

#RUN chown -R www-data:www-data /var/www/html/dobid/writable/uploads/manufacturingfacilitiesdoc/
#RUN chmod -R 775 /var/www/html/dobid/writable/uploads/manufacturingfacilitiesdoc

#RUN chown -R www-data:www-data /var/www/html/dobid/writable/uploads/rawmaterialproof/
#RUN chmod -R 775 /var/www/html/dobid/writable/uploads/rawmaterialproof

#RUN chown -R www-data:www-data /var/www/html/dobid/writable/uploads/supportingdocs/
#RUN chmod -R 775 /var/www/html/dobid/writable/uploads/supportingdocs

# Copy php.ini-production to php.ini
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Modify upload_max_filesize and post_max_size in php.ini
RUN sed -i 's/upload_max_filesize = .*/upload_max_filesize = 10M/' /usr/local/etc/php/php.ini \
    && sed -i 's/post_max_size = .*/post_max_size = 50M/' /usr/local/etc/php/php.ini

# Install nano editor
RUN apt-get clean && \
    apt-get update && \
    apt-get install -y nano && \
    rm -rf /var/lib/apt/lists/*

# Restart Apache
RUN service apache2 restart

