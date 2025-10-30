FROM php:8.2-apache

# Instala SQLite y herramientas necesarias
RUN apt-get update && apt-get install -y libsqlite3-dev pkg-config

# Instala y habilita PDO + SQLite
RUN docker-php-ext-install pdo pdo_sqlite

# Copia el sitio al directorio de Apache
COPY ./grupo1-3pm /var/www/html/

# Ajusta permisos (por si el sistema necesita escribir en SQLite)
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expone el puerto 80
EXPOSE 80

# Inicia Apache
CMD ["apache2-foreground"]
