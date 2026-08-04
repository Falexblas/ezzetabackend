FROM php:8.3-apache
WORKDIR /var/www/html

# 1. Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    libjpeg-dev libfreetype6-dev zip unzip libpq-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd zip xml intl opcache

RUN a2enmod rewrite

# 2. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Copiar SOLO composer.json (El .dockerignore bloqueará el composer.lock y el vendor)
COPY composer.json ./

# 4. FORZAR a Linux a generar el vendor y el lock desde cero (100% limpio)
ENV COMPOSER_MEMORY_LIMIT=-1
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# 5. AHORA sí, copiar el resto del código de tu app
COPY . /var/www/html/

# 6. SEGURO NUCLEAR: Destruir cualquier vendor o lock que haya logrado colarse
RUN rm -rf /var/www/html/vendor /var/www/html/composer.lock

# 7. Volver a instalar para garantizar que el vendor sea el que acabamos de generar en el paso 4
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# 8. Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]