FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Build ke time hi vendor packages install ho jayenge (Ignore platform check ke sath)
RUN composer install --no-dev --ignore-platform-reqs --optimize-autoloader

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config (DEBUG true rakha hai taaki koi error ho to screen pe dikhe)
ENV APP_ENV production
ENV APP_DEBUG true
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]