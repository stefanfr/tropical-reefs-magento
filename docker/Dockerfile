FROM gitlab.hostplus.nl:5050/tropical-reefs/docker/php8.1:latest as build

# Setup cron jobs
RUN apt-get update &&  \
    apt-get -y install cron supervisor &&  \
    apt-get clean

COPY ./docker/php/crontab /etc/cron.d/crontab
COPY ./docker/php/supervisor_cron.conf /etc/supervisor/conf.d/

RUN chmod 0644 /etc/cron.d/crontab && crontab /etc/cron.d/crontab && chmod 0644 /etc/supervisor/conf.d/

# Copy custom config
COPY ./docker/php/zzz-tropicalreefs-fpm.ini /usr/local/etc/php-fpm.d/

# Copy Magento files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Run composer install
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && composer install --no-dev  \
    && rm -f /usr/local/bin/composer

# Run setup Magento
RUN /bin/cp /var/www/html/docker/build-config/env.php /var/www/html/magento/app/etc/env.php  \
    && make di-compile static-content  \
    && /bin/rm /var/www/html/magento/app/etc/env.php  \
    && /bin/rm -Rf /var/www/html/magento/var/cache/* \
    && /bin/rm /var/www/html/magento/var/page_cache/*

# Stage 2: Deploy
FROM nginx:1.23

# Copy files from build stage
COPY --from=build /var/www/html /var/www/html

# Copy nginx configuration
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Start nginx
CMD ["nginx", "-g", "daemon off;"]
