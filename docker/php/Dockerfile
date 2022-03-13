FROM php:7.4.28-apache

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu
RUN docker-php-ext-enable apcu

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
  && php composer-setup.php \
  && php -r "unlink('composer-setup.php');" \
  && mv composer.phar /usr/local/bin/composer

RUN set -ex; \
    apt-get update; \
    apt-get install -y --no-install-recommends mariadb-client procps

WORKDIR /var/www/html

COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir "CityInPill"
#COPY . /var/www/html/CityInPill
#RUN chown -R www-data CityInPill

RUN PATH=$PATH:/var/www/html/CityInPill/vendor/bin:bin