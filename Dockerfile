# Etapa base para configuraciones comunes de PHP y Nginx
FROM php:8.1-fpm-alpine AS base

ENV MY_PROJECT_ROOT /var/www/html

# Instalar Nginx y configurar PHP
RUN apk update && \
    apk add --no-cache nginx && \
    docker-php-ext-install pdo_mysql && \
    rm -rf /var/cache/apk/*  # Limpia el caché

# Configurar Nginx
COPY nginx.conf /etc/nginx/nginx.conf
COPY default.conf /etc/nginx/conf.d/default.conf

# Crear y preparar directorios necesarios con los permisos adecuados
RUN mkdir -p /var/www/html/storage/logs \
    /var/lib/nginx/tmp/client_body \
    /var/lib/nginx/logs && \
    chown -R nginx:nginx /var/lib/nginx/logs /var/lib/nginx/tmp

WORKDIR $MY_PROJECT_ROOT

# Copiar el script de entrada y configurarlo
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer los puertos necesarios
EXPOSE 80

# Etapa de desarrollo con herramientas adicionales como Git y Composer
FROM base AS dev

RUN apk update && \
    apk add --no-cache git curl && \
    rm -rf /var/cache/apk/*  # Limpia el caché
COPY --from=composer:2.6.6 /usr/bin/composer /usr/local/bin/composer

# Etapa de compilación para instalar dependencias de Composer
FROM dev AS compile

# Copiar el código fuente de la aplicación y archivos de configuración
COPY . .

# Instalar dependencias de Composer (Ajustar según sea necesario para entornos de producción)
RUN composer install --no-interaction --no-progress --optimize-autoloader

# Etapa final para despliegue
FROM base AS deploy

# Copiar el código compilado de la etapa anterior
COPY --from=compile $MY_PROJECT_ROOT .

# Establecer el script de entrada como comando por defecto
ENTRYPOINT ["/entrypoint.sh"]
