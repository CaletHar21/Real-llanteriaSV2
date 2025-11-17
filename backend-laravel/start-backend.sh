#!/bin/bash

echo "🚀 Iniciando backend Laravel con PHP-FPM + Nginx..."
echo "==============================="

# Generar APP_KEY si no existe
if ! grep -q "^APP_KEY=" .env || grep -q "^APP_KEY=$" .env; then
    echo "🔑 Generando APP_KEY..."
    php artisan key:generate --force 2>&1 || true
fi

echo "⏳ Esperando que MySQL esté disponible..."
# Esperar hasta que MySQL esté disponible
max_attempts=30
attempt=1

while [ $attempt -le $max_attempts ]; do
    if timeout 5 bash -c "</dev/tcp/db/3306" 2>/dev/null; then
        echo "✅ MySQL está disponible!"
        break
    fi
    
    echo "Intento $attempt/$max_attempts - MySQL no disponible, esperando..."
    sleep 3
    ((attempt++))
done

if [ $attempt -gt $max_attempts ]; then
    echo "❌ No se pudo conectar a MySQL, iniciando servidor sin DB..."
else
    echo "📊 Ejecutando migraciones..."
    php artisan migrate --force 2>&1 || true
    
    echo "🌱 Ejecutando seeders..."
    php artisan db:seed --class=DatosPruebaSeeder --force 2>&1 || true
    
    echo "✅ Base de datos configurada!"
fi

echo "🌐 Iniciando servidor Laravel en puerto 3000..."
php artisan serve --host=0.0.0.0 --port=3000 --no-reload
