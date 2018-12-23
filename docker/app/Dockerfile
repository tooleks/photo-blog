FROM php:7.3-fpm

# Install PHP extensions/modules.
RUN apt-get update -y \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install tokenizer \
    && apt-get install -y libxml2-dev \
    && docker-php-ext-install xml \
    && docker-php-ext-install ctype \
    && docker-php-ext-install json \
    && docker-php-ext-install bcmath \
    && apt-get install -y libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install opcache \
    && docker-php-ext-install exif \
    && apt-get install -y libjpeg-dev libpng-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg-dir=/usr/include --with-png-dir=/usr/include --with-freetype-dir=/usr/include \
    && docker-php-ext-install gd \
    && apt-get install -y libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && pecl install redis \
    && docker-php-ext-enable --ini-name pecl.ini imagick redis \
    && apt-get -y clean \
    && rm -rf /var/lib/apt/lists/*

# Copy PHP configuration.
COPY ./php.ini-production /usr/local/etc/php/php.ini

# Copy Imagick configuration.
COPY ./policy.xml /etc/ImageMagick-6/policy.xml

# Install Composer.
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === trim(file_get_contents('https://composer.github.io/installer.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Install Node.js, npm.
RUN apt-get update -y \
    && apt-get install -y \
        apt-transport-https \
        lsb-release \
        gnupg \
    && curl -sL https://deb.nodesource.com/setup_10.x | bash - \
    && apt-get install -y nodejs \
    && apt-get -y clean \
    && rm -rf /var/lib/apt/lists/*

# Change the working directory.
WORKDIR /var/www/app

# Install the backend application dependencies.
COPY ./database ./database
COPY ./composer.json ./composer.json
COPY ./composer.lock ./composer.lock
RUN composer install \
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
