# Script para construir y ejecutar la aplicación Docker con datos automáticos
# 
# PREREQUISITOS:
# 1. Asegúrate de que Docker Desktop esté ejecutándose

Write-Host "====== LLANTERIA SV2 - CONSTRUCCIÓN AUTOMÁTICA ======" -ForegroundColor Green
Write-Host ""

# Verificar si Docker está ejecutándose
Write-Host "Verificando Docker..." -ForegroundColor Yellow
try {
    docker ps | Out-Null
    Write-Host "✅ Docker está ejecutándose" -ForegroundColor Green
} catch {
    Write-Host "❌ Error: Docker no está ejecutándose o no está disponible" -ForegroundColor Red
    Write-Host "Por favor, inicia Docker Desktop y ejecuta este script nuevamente" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "PASO 1: DETENIENDO CONTENEDORES EXISTENTES" -ForegroundColor Cyan
docker-compose down

Write-Host ""
Write-Host "PASO 2: CONSTRUYENDO IMÁGENES" -ForegroundColor Cyan
docker-compose build --no-cache

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Imágenes construidas exitosamente" -ForegroundColor Green
} else {
    Write-Host "❌ Error construyendo las imágenes" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "PASO 3: INICIANDO CONTENEDORES" -ForegroundColor Cyan
docker-compose up -d

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Contenedores iniciados exitosamente" -ForegroundColor Green
} else {
    Write-Host "❌ Error iniciando los contenedores" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "PASO 4: ESPERANDO CONFIGURACIÓN DE BASE DE DATOS..." -ForegroundColor Cyan
Write-Host "(El backend ejecutará automáticamente migraciones y seeders)" -ForegroundColor Yellow

Start-Sleep -Seconds 30

Write-Host ""
Write-Host "ESTADO DE LOS CONTENEDORES" -ForegroundColor Cyan
docker-compose ps

Write-Host ""
Write-Host "====== APLICACIÓN LISTA ======" -ForegroundColor Green
Write-Host "Frontend (React): http://localhost:5173" -ForegroundColor White
Write-Host "Backend (Laravel): http://localhost:3000" -ForegroundColor White
Write-Host "Base de datos (MySQL): localhost:3307" -ForegroundColor White
Write-Host ""
Write-Host "USUARIOS DE PRUEBA:" -ForegroundColor Yellow
Write-Host "- caleth.torrez17@itca.edu.sv (contraseña: 1234566789) - ADMIN" -ForegroundColor White
Write-Host "- cointra@llanteria.com (contraseña: 1234566789) - AGENTE" -ForegroundColor White
Write-Host "- cliente@llanteria.com (contraseña: 1234566789) - CLIENTE" -ForegroundColor White
Write-Host ""
Write-Host "DATOS INCLUIDOS:" -ForegroundColor Yellow
Write-Host "- 8 Marcas de llantas (Michelin, Bridgestone, Pirelli, etc.)" -ForegroundColor White
Write-Host "- Modelos para cada marca" -ForegroundColor White
Write-Host "- 3 Llantas de prueba con precios y stock" -ForegroundColor White
Write-Host ""
Write-Host "Para ver los logs: docker-compose logs -f" -ForegroundColor Gray
Write-Host "Para detener: docker-compose down" -ForegroundColor Gray