# Imagen base con PHP
FROM php:8.2-cli

# Instalar extensiones necesarias para PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && rm -rf /var/lib/apt/lists/*

# Establecer directorio de trabajo
WORKDIR /app

# Copiar todos los archivos del proyecto al contenedor
COPY . .

# Exponer el puerto que Render usará
EXPOSE 10000

# Comando para iniciar el servidor PHP embebido
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
