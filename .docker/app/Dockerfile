FROM php:5.6-fpm

ARG user
ARG uid

RUN apt-get update -qq \
 && apt-get install -qq -y --no-install-recommends \
    g++ \
    zip \
    unzip \
    curl \
    git \
    ca-certificates \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libmemcached-dev \
    zlib1g-dev \
    libmcrypt-dev \
    libssl-dev \
    libz-dev \
    libsqlite3-dev \
    libxml2-dev \
    libcurl3-dev \
    libedit-dev \
    libpspell-dev \
    libldap2-dev \
    unixodbc-dev \
    libmemcached11 \
    libmemcachedutil2 \
    libmagickwand-dev \
    libpq-dev \
    zlib1g-dev \
    libicu-dev \
    ssmtp \
    mailutils \
    imagemagick

# PHP
RUN docker-php-ext-install -j$(nproc) iconv mysql mysqli mcrypt pdo_mysql intl pcntl zip bcmath simplexml xmlrpc soap pspell mbstring \
 && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
 && docker-php-ext-install -j$(nproc) gd intl \
 && docker-php-ext-enable mysql mysqli mcrypt pdo_mysql pcntl zip bcmath simplexml xmlrpc soap pspell mbstring

# instal pgsql, pdo and pdo_pgsql
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
 && docker-php-ext-install pgsql pdo pdo_pgsql

# MEMCACHE
RUN CFLAGS="-fgnu89-inline" pecl install memcache-3.0.8 \
 && echo 'extension=memcache.so' > ${PHP_INI_DIR}/conf.d/ext-memcache.ini

# IMAGICK
RUN pecl install imagick \
 && docker-php-ext-enable imagick

# TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
 && echo ${TIMEZONE} > /etc/timezone

# PHP CONFIGURATION
RUN cat ${PHP_INI_DIR}/php.ini-production >                                             ${PHP_INI_DIR}/php.ini \
 && sed -i 's/;error_log = .*/error_log = \/proc\/self\/fd\/2/g'                        ${PHP_INI_DIR}/php.ini \
 && sed -i 's/;access.log = .*/access.log = \/proc\/self\/fd\/2/g'                      ${PHP_INI_DIR}/php.ini \
 && sed -i 's/short_open_tag = Off/short_open_tag = On/g'                               ${PHP_INI_DIR}/php.ini \
 && sed -i 's/;always_populate_raw_post_data = -1/always_populate_raw_post_data = -1/g' ${PHP_INI_DIR}/php.ini \
 && sed -i 's/;sendmail_path.*/sendmail_path = "\/usr\/sbin\/ssmtp -t"/g'               ${PHP_INI_DIR}/php.ini \
 && sed -i 's/upload_max_filesize = .*/upload_max_filesize = 100M/g'                    ${PHP_INI_DIR}/php.ini \
 && sed -i 's/post_max_size = .*/post_max_size = 100M/g'                                ${PHP_INI_DIR}/php.ini \
 && sed -i 's/max_execution_time = .*/max_execution_time = 600/g'                                ${PHP_INI_DIR}/php.ini \
 && sed -i 's/pm.max_children = .*/pm.max_children = 16/g'                              ${PHP_INI_DIR}-fpm.d/www.conf \
 && sed -i 's/;pm.max_requests = .*/pm.max_requests = 1000/g'                           ${PHP_INI_DIR}-fpm.d/www.conf \
 && sed -i 's/;php_admin_flag\[log_errors\] = .*/php_admin_flag[log_errors] = On/g'     ${PHP_INI_DIR}-fpm.d/www.conf \
 && sed -i 's/;request_terminate_timeout = .*/request_terminate_timeout = 600/g'     ${PHP_INI_DIR}-fpm.d/www.conf \
 && echo   'date.timezone=${TIMEZONE}\n' >                                              ${PHP_INI_DIR}/conf.d/ext-timezone.ini

# COMPOSER
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

RUN useradd -G www-data,root -u ${uid} -d /home/${user} ${user} \
 && mkdir -p /home/${user}/.composer /apps/meritokrat \
 && chown -R ${user}:${user} /home/${user}/.composer /apps/meritokrat

WORKDIR /apps/meritokrat

# CLEANUP
RUN apt-get -y autoclean \
 && apt-get -y autoremove \
 && apt-get -y clean \
 && rm -rf /var/lib/apt/lists/* \
 && apt-get update
FROM php:5.6-fpm

ARG user
ARG uid

RUN apt-get update -qq \
 && apt-get install -qq -y --no-install-recommends \
    g++ \
    zip \
    unzip \
    curl \
    git \
    ca-certificates \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libmemcached-dev \
    zlib1g-dev \
    libmcrypt-dev \
    libssl-dev \
    libz-dev \
    libsqlite3-dev \
    libxml2-dev \
    libcurl3-dev \
    libedit-dev \
    libpspell-dev \
    libldap2-dev \
    unixodbc-dev \
    libmemcached11 \
    libmemcachedutil2 \
    libmagickwand-dev \
    libpq-dev \
    zlib1g-dev \
    libicu-dev \
    ssmtp \
    mailutils \
    imagemagick

# PHP
RUN docker-php-ext-install -j$(nproc) iconv mysql mysqli mcrypt pdo_mysql intl pcntl zip bcmath simplexml xmlrpc soap pspell mbstring \
 && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
 && docker-php-ext-install -j$(nproc) gd intl \
 && docker-php-ext-enable mysql mysqli mcrypt pdo_mysql pcntl zip bcmath simplexml xmlrpc soap pspell mbstring

# instal pgsql, pdo and pdo_pgsql
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
 && docker-php-ext-install pgsql pdo pdo_pgsql

# MEMCACHE
RUN CFLAGS="-fgnu89-inline" pecl install memcache-3.0.8 \
 && echo 'extension=memcache.so' > ${PHP_INI_DIR}/conf.d/ext-memcache.ini

# IMAGICK
RUN pecl install imagick \
 && docker-php-ext-enable imagick

# TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
 && echo ${TIMEZONE} > /etc/timezone

# PHP CONFIGURATION
RUN cat ${PHP_INI_DIR}/php.ini-production >                                             ${PHP_INI_DIR}/php.ini \
 && sed -i 's/;error_log = .*/error_log = \/proc\/self\/fd\/2/g'                        ${PHP_INI_DIR}/php.ini \
 && sed -i 's/;access.log = .*/access.log = \/proc\/self\/fd\/2/g'                      ${PHP_INI_DIR}/php.ini \
 && sed -i 's/short_open_tag = Off/short_open_tag = On/g'                               ${PHP_INI_DIR}/php.ini \
 && sed -i 's/;always_populate_raw_post_data = -1/always_populate_raw_post_data = -1/g' ${PHP_INI_DIR}/php.ini \
 && sed -i 's/;sendmail_path.*/sendmail_path = "\/usr\/sbin\/ssmtp -t"/g'               ${PHP_INI_DIR}/php.ini \
 && sed -i 's/upload_max_filesize = .*/upload_max_filesize = 100M/g'                    ${PHP_INI_DIR}/php.ini \
 && sed -i 's/post_max_size = .*/post_max_size = 100M/g'                                ${PHP_INI_DIR}/php.ini \
 && sed -i 's/max_execution_time = .*/max_execution_time = 600/g'                                ${PHP_INI_DIR}/php.ini \
 && sed -i 's/pm.max_children = .*/pm.max_children = 16/g'                              ${PHP_INI_DIR}-fpm.d/www.conf \
 && sed -i 's/;pm.max_requests = .*/pm.max_requests = 1000/g'                           ${PHP_INI_DIR}-fpm.d/www.conf \
 && sed -i 's/;php_admin_flag\[log_errors\] = .*/php_admin_flag[log_errors] = On/g'     ${PHP_INI_DIR}-fpm.d/www.conf \
 && sed -i 's/;request_terminate_timeout = .*/request_terminate_timeout = 600/g'     ${PHP_INI_DIR}-fpm.d/www.conf \
 && echo   'date.timezone=${TIMEZONE}\n' >                                              ${PHP_INI_DIR}/conf.d/ext-timezone.ini

# COMPOSER
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

RUN useradd -G www-data,root -u ${uid} -d /home/${user} ${user} \
 && mkdir -p /home/${user}/.composer /apps/meritokrat \
 && chown -R ${user}:${user} /home/${user}/.composer /apps/meritokrat

WORKDIR /apps/meritokrat

# CLEANUP
RUN apt-get -y autoclean \
 && apt-get -y autoremove \
 && apt-get -y clean \
 && rm -rf /var/lib/apt/lists/* \
 && apt-get update
