FROM php:8.2-apache

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by CodeIgniter 4 and Dompdf
RUN docker-php-ext-install intl mysqli pdo pdo_mysql gd zip

# Enable Apache mod_rewrite for CodeIgniter URLs
RUN a2enmod rewrite

# Update Apache default configuration to point to the public/ directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable \
    && chmod -R 775 /var/www/html/public

# Install Composer dependencies (optimised for production)
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["apache2-foreground"]
