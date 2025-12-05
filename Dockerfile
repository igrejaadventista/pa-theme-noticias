FROM php:8.1-fpm-alpine3.18

RUN apk update 
RUN apk upgrade

# Dependências principais
RUN apk add --no-cache bash lcms2-dev g++ make git pkgconfig autoconf automake libtool nasm build-base zlib-dev libpng libpng-dev jpeg-dev libc6-compat npm zip

RUN apk add --no-cache nodejs npm yarn

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY --chown=www-data:www-data . /var/www/build

RUN composer clearcache

RUN cd /var/www/build \
  && composer install --no-dev \
  && composer dump -o \
  && yarn \
  && yarn build:production

RUN cd /var/www/build \
  && rm -rf assets/scss node_modules \
  && find . -type d -name 'node_modules' -exec rm -rf {} + \
  && find . -type d -name '*.git' -exec rm -rf {} + \
  && find . -type f -name '*.map' -exec rm {} + \
  && find . -type f -name '*.json' -exec rm {} + \
  && find . -type f -name '*.rb' -exec rm {} + \
  && find . -type f -name 'composer*' -exec rm {} + \
  && find . -type f -name 'README*' -exec rm {} + \
  && find . -type f -name '*.lock' -exec rm {} + \
  && find . -type f -name '*.mix.*' -exec rm {} + \
  && find . -type f -name '*.txt' -exec rm {} +
  
#  RUN cat /var/www/build/assets/js/script.js
