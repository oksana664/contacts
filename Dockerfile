FROM mileschou/phalcon:7.3-apache

ENV APACHE_DOCUMENT_ROOT /var/www/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite

RUN apt update && apt -y install git unzip libzip-dev vim

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN echo "xdebug.remote_enable=true" >> "$PHP_INI_DIR/php.ini"

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN docker-php-ext-install mysqli pdo pdo_mysql zip
RUN docker-phalcon-install-devtools && ln -sf /usr/src/phalcon-devtools/phalcon /usr/local/bin/phalcon

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php && rm -f composer-setup.php
RUN mv composer.phar /usr/bin/composer && chmod 777 /usr/bin/composer
RUN ln -s /var/www/vendor/phpunit/phpunit/phpunit /usr/bin/phpunit
RUN ln -s /var/www/vendor/codeception/codeception/codecept /usr/bin/codecept