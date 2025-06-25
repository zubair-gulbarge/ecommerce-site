FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy custom php.ini config
COPY config/php.ini /usr/local/etc/php/

# Set working directory
WORKDIR /var/www/html

# Copy app files into container
COPY public/ /var/www/html/
