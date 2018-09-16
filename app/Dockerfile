FROM node:10 as frontend

# Change the working directory.
WORKDIR /home/node/app

# Copy the frontend application package files.
COPY ./package.json ./package.json
COPY ./package-lock.json ./package-lock.json

# Install the frontend application dependencies.
RUN npm install

# Copy the application source code.
COPY . .

# Build the frontend application bundle.
RUN npm run prod

# Generate REST API documentation.
RUN ./node_modules/.bin/apidoc -i ./src/api -o ./docs/rest_api/dist

# Remove non-production dependencies.
RUN npm prune --production


FROM composer:1.7 as vendor

# Copy the backend application package files.
COPY ./database ./database
COPY ./composer.json ./composer.json
COPY ./composer.lock ./composer.lock

# Install the backend application dependencies.
RUN composer install \
 --ignore-platform-reqs \
 --no-interaction \
 --no-plugins \
 --no-scripts \
 --prefer-dist


FROM php:7.2-fpm

# Install system dependencies.
RUN apt-get update \
    && apt-get install -y \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
    && apt-get -y autoclean \
    && apt-get -y autoremove \
    && apt-get -y clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP dependencies.
RUN docker-php-ext-configure gd --with-jpeg-dir=/usr/include --with-png-dir=/usr/include --with-freetype-dir=/usr/include \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) exif \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) opcache \
    && pecl install redis \
    && docker-php-ext-enable redis

# Copy PHP configuration.
COPY ./php.prod.ini /usr/local/etc/php/php.ini

# Change the working directory.
WORKDIR /var/www/app

# Copy the application source code.
COPY . .

# Copy the application artifacts.
COPY --from=frontend /home/node/app/public ./public
COPY --from=frontend /home/node/app/docs ./docs
COPY --from=vendor /app/vendor ./vendor
