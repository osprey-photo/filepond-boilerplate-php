# docker build -t my-php-app .
# docker run -d --name my-running-app my-php-app

FROM php:7.2-apache
COPY public/ /var/www/html/
# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"