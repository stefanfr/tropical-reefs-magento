FROM gitlab.hostplus.nl:5050/tropical-reefs/docker/php8.1:latest as build

# Setup cron jobs
RUN apt-get update &&  \
    apt-get -y install cron supervisor nano &&  \
    apt-get clean

COPY ./docker/build-config/crontab /etc/cron.d/crontab
COPY ./docker/build-config/supervisor_cron.conf /etc/supervisor/conf.d/

RUN chmod 0644 /etc/cron.d/crontab && crontab /etc/cron.d/crontab && chmod 0644 /etc/supervisor/conf.d/

# Copy Magento files
COPY . /var/www/html
COPY ./docker/build-config/auth.json /var/www/html/auth.json

# Set working directory
WORKDIR /var/www/html

# Run composer install
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && /bin/cp /var/www/html/docker/build-config/auth.json /var/www/html/auth.json  \
    && composer install --no-dev --prefer-dist \
    && /bin/rm -f /usr/local/bin/composer \
    && /bin/rm -Rf /var/www/html/auth.json

# Run setup Magento
RUN /bin/cp /var/www/html/docker/build-config/env.php /var/www/html/magento/app/etc/env.php  \
    && make di-compile static-content  \
    && /bin/rm /var/www/html/magento/app/etc/env.php  \
    && /bin/rm -Rf /var/www/html/magento/var/cache/* \
    && /bin/rm -Rf /var/www/html/magento/var/page_cache/*

RUN chown -R www-data:www-data /var/www/html
