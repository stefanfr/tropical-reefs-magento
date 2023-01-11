FROM gitlab.hostplus.nl:5050/tropical-reefs/docker/php8.1:latest
RUN apt-get update && apt-get -y install cron supervisor && apt-get clean
COPY ./crontab /etc/cron.d/crontab
COPY ./supervisor_cron.conf /etc/supervisor/conf.d/
RUN chmod 0644 /etc/cron.d/crontab && crontab /etc/cron.d/crontab && chmod 0644 /etc/supervisor/conf.d/
COPY ./zzz-tropicalreefs-fpm.ini /usr/local/etc/php-fpm.d/
COPY ./ /var/www/html
RUN /bin/cp /var/www/html/docker/build-config/env.php /var/www/html/magento/app/etc/env.php && make di-compile static-content && /bin/rm /var/www/html/magento/app/etc/env.php && /bin/rm -Rf /var/www/html/magento/var/cache/* /var/www/html/magento/var/page_cache/*
RUN chmod 777 /var/www/html
