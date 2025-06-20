FROM php:8.4.7-apache

# Instala extensiones necesarias
RUN docker-php-ext-install mysqli

# Habilita mod_rewrite de Apache (opcional si usas URLs amigables)
RUN a2enmod rewrite

# Copia el contenido del proyecto al contenedor
COPY . /var/www/html/

# Da permisos correctos
RUN chown -R www-data:www-data /var/www/html