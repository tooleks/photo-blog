FROM php:7.1-fpm

# Install system dependencies.
RUN apt-get update \
 && apt-get install -y \
    supervisor \
    cron \
    git \
 && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng12-dev \
    libcurl4-nss-dev \
    libc-client-dev \
    libkrb5-dev \
    firebird2.5-dev \
    libicu-dev \
    libxml2-dev \
    libxslt1-dev \
    ssmtp \
    autoconf \
    zip \
    libssh2-1-dev \
 && apt-get -y autoclean \
 && apt-get -y autoremove \
 && apt-get -y clean \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j$(nproc) mcrypt \
 && docker-php-ext-install -j$(nproc) curl \
 && docker-php-ext-install -j$(nproc) mbstring \
 && docker-php-ext-install -j$(nproc) iconv \
 && docker-php-ext-install -j$(nproc) interbase \
 && docker-php-ext-install -j$(nproc) intl \
 && docker-php-ext-install -j$(nproc) soap \
 && docker-php-ext-install -j$(nproc) xmlrpc \
 && docker-php-ext-install -j$(nproc) xsl \
 && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
 && docker-php-ext-install -j$(nproc) gd \
 && docker-php-ext-install exif \
 && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
 && docker-php-ext-install imap \
 && docker-php-ext-install mysqli pdo pdo_mysql \
 && docker-php-ext-install zip

# Install composer.
RUN curl -sS https://getcomposer.org/installer | php \
 && mv composer.phar /usr/local/bin/composer

# Install nvm, node, npm and node dependencies.
ENV NVM_DIR /usr/local/nvm
ENV NVM_VERSION 0.33.2
ENV NODE_VERSION 8.1.3

RUN curl -sS https://raw.githubusercontent.com/creationix/nvm/v$NVM_VERSION/install.sh | bash
RUN ["/bin/bash", "-c", "source $NVM_DIR/nvm.sh \
                      && nvm install $NODE_VERSION \
                      && nvm alias default $NODE_VERSION \
                      && nvm use default \
                      && npm install -g \
                         yarn \
                         apidoc"]

ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

# Copy the supervisor config file.
COPY ./supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Copy the scheduler config file.
COPY ./scheduler /etc/cron.d/
RUN chmod 0644 /etc/cron.d/scheduler

# Set the application working directory.
WORKDIR /data/backend/

# Copy the entrypoint script.
COPY ./docker-entrypoint.sh /
RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["/usr/bin/supervisord", "-n"]
