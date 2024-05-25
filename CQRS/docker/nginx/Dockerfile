FROM nginx:alpine

WORKDIR /var/www

COPY --link /docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

COPY ./views/assets /var/www/assets
