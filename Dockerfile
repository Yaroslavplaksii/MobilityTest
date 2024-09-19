FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]

EXPOSE 9000
