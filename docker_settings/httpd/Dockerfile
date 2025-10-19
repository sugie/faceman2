FROM php:8.4-apache
RUN docker-php-ext-install pdo_mysql

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    unzip \
    less \
    sudo \
    dnsutils

RUN apt-get update && apt-get install -y --no-install-recommends \
    libffi-dev libssl-dev \
    python3 python3-pip python3-dev python-is-python3 \
 && python3 -m pip install --no-cache-dir backports.csv tabulate terminaltables wcwidth \
 && python3 -m pip install --no-cache-dir --upgrade setuptools \
 && python3 -m pip install --no-cache-dir mycli \
 && apt-get purge -y --auto-remove libffi-dev libssl-dev python3-dev \
 && apt-get purge -y python3-pip || true \
 && rm -rf /var/lib/apt/lists/*


RUN apt update && apt install -y curl wget vim  net-tools iputils-ping bind9-utils default-mysql-client openssh-server tree

RUN apt update \
  && apt install -y task-japanese locales locales-all \
  && locale-gen ja_JP.UTF-8 \
  && echo "export LANG=ja_JP.UTF-8" >> ~/.bashrc


RUN docker-php-ext-configure pcntl --enable-pcntl && docker-php-ext-install pcntl \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install gd pdo pdo_mysql zip

# Enable Apache mod_rewrite for Laravel's .htaccess file
RUN a2enmod rewrite

# Clear out the local repository of retrieved package files
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://getcomposer.org/installer | php &&  mv composer.phar /usr/local/bin/composer
# composer self-update --2 が必要かも。

RUN docker-php-ext-install pdo_mysql


# Run Composer install to set up the Laravel project (assuming you've copied all the application files)
#RUN composer install --optimize-autoloader --no-dev --no-interaction

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html

COPY --chmod=0655 bashrc /root/.bashrc
COPY --chmod=0655 vimrc /root/.vimrc
COPY --chmod=0600 mycnf /root/.my.cnf
COPY --chmod=0600 _myclirc /root/.myclirc

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY _env_for_docker /var/www/fm2/app/.env

RUN ln -s /usr/local/bin/php /usr/bin/php

EXPOSE 80

# usr_local_bin 以下のファイルを /usr/local/bin に配置
COPY usr_local_bin/* /usr/local/bin/

# 実行権限を付与
RUN chmod +x /usr/local/bin/*

