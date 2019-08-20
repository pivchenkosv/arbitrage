FROM php:7.3-apache-stretch

WORKDIR /var/www

COPY . /var/www
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start

RUN chown -R www-data:www-data /var/www \
    && chmod u+x /usr/local/bin/start \
    && a2enmod rewrite

#RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

RUN chmod -R 775 storage/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["/usr/local/bin/start"]
