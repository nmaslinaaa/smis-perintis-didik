FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

COPY . .

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php \
 && mv composer.phar /usr/local/bin/composer \
 && rm composer-setup.php

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 8000

CMD php -S 0.0.0.0:$PORT -t public
