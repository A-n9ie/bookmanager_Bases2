FROM php:8.2-apache

# Instala SQLite y extensiones necesarias
RUN apt-get update && apt-get install -y libsqlite3-dev pkg-config \
    && docker-php-ext-install pdo pdo_sqlite

# Crea el directorio del sitio y da permisos a Apache
RUN mkdir -p /var/www/html && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Exponer puerto
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
