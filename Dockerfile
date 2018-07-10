FROM composer

COPY composer.lock /app/composer.lock
COPY composer.json /app/composer.json
WORKDIR /app
RUN composer install

FROM php:7.2-cli-alpine

COPY src /app/src
COPY --from=0 /app/vendor /app/vendor
COPY clouddk.php /app/clouddk.php

WORKDIR /app

ENTRYPOINT ["php", "clouddk.php"]
