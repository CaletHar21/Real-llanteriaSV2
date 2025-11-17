FROM php:8.2-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

# Crear .env si no existe
RUN if [ ! -f .env ]; then cp .env.example .env 2>/dev/null || echo 'APP_KEY=base64:rGrREW3XdMHfVLnFr2Sn7ylxrGHTqwA3sefuXp5fu94=' > .env; fi

# Asegurar que APP_KEY está configurado
RUN grep -q 'APP_KEY=' .env || echo 'APP_KEY=base64:rGrREW3XdMHfVLnFr2Sn7ylxrGHTqwA3sefuXp5fu94=' >> .env

RUN composer install --no-interaction --no-dev --optimize-autoloader 2>&1 || true

RUN chmod +x start-backend.sh

RUN chmod +x start-backend.sh

EXPOSE 3000

CMD ["bash", "start-backend.sh"]
