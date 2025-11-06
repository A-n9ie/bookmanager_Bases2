FROM php:8.2-apache

# Instala dependencias necesarias para SQLite, PDF y Excel
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_sqlite gd zip

# Habilita mod_rewrite (rutas limpias)
RUN a2enmod rewrite

# Crea el directorio del sitio y da permisos a Apache
RUN mkdir -p /var/www/html && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Copia el contenido de tu proyecto al contenedor
COPY . /var/www/html/

# Copiar Composer desde imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instala las dependencias necesarias para exportar PDF y Excel
WORKDIR /var/www/html
RUN composer require mpdf/mpdf \
    && composer require phpoffice/phpspreadsheet

# Permisos finales
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Comando de inicio del servidor Apache
CMD ["apache2-foreground"]
