FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

COPY . /var/www/html/

# Fix port for Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 10000

CMD ["apache2-foreground"]