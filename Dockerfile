# ETAPA 1: Construir dependencias en un entorno 100% limpio de Linux
FROM composer:2.7 AS vendor_builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --prefer-dist

# ETAPA 2: Imagen final de PHP
FROM php:8.3-apache
WORKDIR /var/www/html

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    libjpeg-dev libfreetype6-dev zip unzip libpq-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd zip xml intl opcache

RUN a2enmod rewrite

# Copiar el código de la app (El .dockerignore debería bloquear el vendor local)
COPY . /var/www/html/

# 🔥 SEGURO ANTI-COLADO: Destruir cualquier vendor que haya logrado pasar 🔥
RUN rm -rf /var/www/html/vendor

# Copiar el vendor LIMPIO y fresco desde la Etapa 1
COPY --from=vendor_builder /app/vendor /var/www/html/vendor

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]