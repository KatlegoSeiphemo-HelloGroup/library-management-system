FROM php:8.3-apache

LABEL authors="katlego.seiphemo"

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli

COPY src/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
