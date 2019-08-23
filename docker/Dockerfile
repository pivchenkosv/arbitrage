FROM php:7.3-apache

WORKDIR /var/www

COPY . /var/www
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start

RUN apt-get update \
    && apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev
RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage/ \
    && chmod u+x /usr/local/bin/start \
    && a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get install --yes zip unzip
RUN composer install --prefer-dist

RUN php artisan config:cache

CMD ["/usr/local/bin/start"]
