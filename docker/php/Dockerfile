FROM php:8.1.5-fpm

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev libxslt-dev zip libzip-dev zip curl wget unzip \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt install -y nodejs \
    && npm install --global yarn \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl opcache pdo pdo_mysql xsl\
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && pecl install pcov \
    && docker-php-ext-enable pcov

WORKDIR /var/www/html

COPY --from=composer:2.2.12 /usr/bin/composer /usr/local/bin/composer

RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv ~/.symfony/bin/symfony /usr/local/bin/symfony

# RUN pecl install xdebug
# COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
# RUN docker-php-ext-enable xdebug

