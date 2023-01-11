FROM gitlab.hostplus.nl:5050/tropical-reefs/magento/magento-php:latest as stage-a
FROM gitlab.hostplus.nl:5050/tropical-reefs/docker/magento-nginx:latest
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY --from=stage-a /var/www/html /var/www/html
