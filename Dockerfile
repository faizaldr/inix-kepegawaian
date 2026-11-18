FROM php:8.2-apache

# Install ekstensi PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Ubah DocumentRoot ke folder public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Izinkan konfigurasi .htaccess Rewrite
RUN sed -i '/<Directory \${APACHE_DOCUMENT_ROOT}>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
