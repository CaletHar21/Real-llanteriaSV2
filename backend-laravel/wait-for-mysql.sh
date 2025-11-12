#!/bin/bash

# Script para esperar a que MySQL esté disponible usando herramientas básicas
echo "Esperando a que MySQL esté disponible en host: db puerto: 3306..."

# Función para verificar conexión MySQL usando netcat o curl
wait_for_mysql() {
    local host="db"
    local port="3306"
    local max_attempts=30
    local attempt=1

    while [ $attempt -le $max_attempts ]; do
        echo "Intento $attempt de $max_attempts - Verificando MySQL..."
        
        # Usar timeout para evitar colgarse
        if timeout 5 bash -c "</dev/tcp/$host/$port" 2>/dev/null; then
            echo "✅ MySQL está disponible!"
            return 0
        fi
        
        echo "MySQL no está disponible aún, esperando 5 segundos..."
        sleep 5
        ((attempt++))
    done
    
    echo "❌ No se pudo conectar a MySQL después de $max_attempts intentos"
    return 1
}

# Esperar a MySQL
if wait_for_mysql; then
    echo "✅ Iniciando migraciones..."
    
    # Ejecutar migraciones y seeders
    php artisan migrate:fresh --seed --force
    
    if [ $? -eq 0 ]; then
        echo "✅ Migraciones y seeders completados exitosamente!"
    else
        echo "❌ Error en migraciones, continuando con el servidor..."
    fi
    
    echo "🚀 Iniciando servidor Laravel..."
    php artisan serve --host=0.0.0.0 --port=3000
else
    echo "❌ No se pudo conectar a MySQL. Iniciando servidor sin migraciones..."
    php artisan serve --host=0.0.0.0 --port=3000
fi