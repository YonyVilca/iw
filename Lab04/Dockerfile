# Usa la imagen base de PHP 8.1 con Apache
FROM php:8.1-apache

# Configura la zona horaria
RUN echo "America/Lima" > /etc/timezone && \
    ln -sf /usr/share/zoneinfo/America/Lima /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata

# Instala dependencias necesarias y extensiones de PHP para MySQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mysqli

# Habilita el módulo mod_rewrite de Apache
RUN a2enmod rewrite

# Copia los archivos de la aplicación al directorio predeterminado de Apache
COPY . /var/www/html/

# Ajusta permisos para el directorio de Apache
RUN chown -R www-data:www-data /var/www/html/

# Expone el puerto 8086 para que Docker lo utilice
EXPOSE 8086

# Cambiar el puerto de Apache de 80 a 8086
RUN sed -i 's/80/8086/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Inicia Apache en el contenedor
CMD ["apache2-foreground"]

