# Scripts útiles para Docker - Llantería SV2
# 
# COMANDOS PRINCIPALES:

Write-Host "=== COMANDOS DOCKER DISPONIBLES ===" -ForegroundColor Green
Write-Host ""

$choice = Read-Host @"
Selecciona una opción:
1. Construir y ejecutar todo
2. Solo ejecutar (sin construir)
3. Detener todos los contenedores
4. Ver logs en tiempo real
5. Ver estado de contenedores
6. Limpiar todo (imágenes, contenedores, volúmenes)
7. Entrar al contenedor backend
8. Entrar al contenedor frontend
9. Resetear base de datos
Opción
"@

switch ($choice) {
    "1" {
        Write-Host "Construyendo y ejecutando..." -ForegroundColor Cyan
        docker-compose down
        docker-compose build --no-cache
        docker-compose up -d
        docker-compose ps
    }
    "2" {
        Write-Host "Ejecutando contenedores..." -ForegroundColor Cyan
        docker-compose up -d
        docker-compose ps
    }
    "3" {
        Write-Host "Deteniendo contenedores..." -ForegroundColor Cyan
        docker-compose down
    }
    "4" {
        Write-Host "Mostrando logs (Ctrl+C para salir)..." -ForegroundColor Cyan
        docker-compose logs -f
    }
    "5" {
        Write-Host "Estado de contenedores:" -ForegroundColor Cyan
        docker-compose ps
    }
    "6" {
        Write-Host "¿Estás seguro? Esto eliminará TODOS los datos. (s/N): " -ForegroundColor Red -NoNewline
        $confirm = Read-Host
        if ($confirm -eq "s" -or $confirm -eq "S") {
            docker-compose down -v --rmi all
            docker system prune -a
        }
    }
    "7" {
        Write-Host "Entrando al contenedor backend..." -ForegroundColor Cyan
        docker exec -it llanteria-sv2-backend bash
    }
    "8" {
        Write-Host "Entrando al contenedor frontend..." -ForegroundColor Cyan
        docker exec -it llanteria-sv2-frontend sh
    }
    "9" {
        Write-Host "Reseteando base de datos..." -ForegroundColor Cyan
        docker exec -it llanteria-sv2-backend php artisan migrate:fresh --seed
    }
    default {
        Write-Host "Opción no válida" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "=== INFORMACIÓN ÚTIL ===" -ForegroundColor Green
Write-Host "Frontend: http://localhost:5173"
Write-Host "Backend: http://localhost:3000"
Write-Host "Base de datos: localhost:3307"