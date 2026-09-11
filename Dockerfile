FROM php:8.4-apache

# Install system dependencies and MySQL extension
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    git \
    && docker-php-ext-install zip pdo pdo_mysql

# Install official latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set Apache DocumentRoot to Laravel's public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable Apache mod_rewrite for Laravel routes (.htaccess)
RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set working directory & copy application code
WORKDIR /var/www/html
COPY . .

# Install composer packages inside the build (with PHP 8.4 native support)
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose web port
EXPOSE 80

# Auto-run migrations and start Apache
CMD php artisan config:clear && php artisan migrate --force && apache2-foreground