FROM php:7.2-fpm

# Update dependencies list and install system dependencies.
RUN apt-get update -yqq

# Install PHP dependencies.
RUN apt-get install -y \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg-dir=/usr/include --with-png-dir=/usr/include --with-freetype-dir=/usr/include \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) exif \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) opcache \
    && pecl install redis \
    && docker-php-ext-enable redis

# Copy PHP configuration.
COPY ./php.ini-production /usr/local/etc/php/php.ini

# Install Composer.
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === trim(file_get_contents('https://composer.github.io/installer.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Install Node.js, npm.
RUN apt install -y apt-transport-https lsb-release gnupg \
    && curl -sL https://deb.nodesource.com/setup_10.x | bash - \
    && apt-get install -y nodejs

# Change the working directory.
WORKDIR /var/www/app

# Install the backend application dependencies.
COPY ./database ./database
COPY ./composer.json ./composer.json
COPY ./composer.lock ./composer.lock
RUN composer install \
 --ignore-platform-reqs \
 --no-interaction \
 --no-plugins \
 --no-scripts \
 --prefer-dist

# Install the frontend application dependencies.
COPY ./package.json ./package.json
COPY ./package-lock.json ./package-lock.json
RUN npm install

# Copy the application source code.
COPY . .

# Build the frontend application bundle.
RUN npm run prod

# Generate REST API documentation.
RUN ./node_modules/.bin/apidoc -i ./src/api -o ./docs/rest_api/dist

# Clean up.
RUN apt-get -y autoclean \
    && apt-get -y autoremove \
    && apt-get -y clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm /var/log/lastlog /var/log/faillog