# Script para construir y ejecutar la aplicacion Docker con datos automaticos

Write-Host "====== LLANTERIA SV2 - CONSTRUCCION AUTOMATICA ======" -ForegroundColor Green
Write-Host ""

# Verificar si Docker esta ejecutandose
Write-Host "Verificando Docker..." -ForegroundColor Yellow
try {
    docker ps | Out-Null
    Write-Host "Docker esta ejecutandose" -ForegroundColor Green
} catch {
    Write-Host "ERROR: Docker no esta ejecutandose" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "PASO 1: DETENIENDO CONTENEDORES EXISTENTES" -ForegroundColor Cyan
docker-compose down

Write-Host ""
Write-Host "PASO 2: CONSTRUYENDO IMAGENES" -ForegroundColor Cyan
docker-compose build --no-cache

if ($LASTEXITCODE -eq 0) {
    Write-Host "Imagenes construidas exitosamente" -ForegroundColor Green
} else {
    Write-Host "ERROR construyendo las imagenes" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "PASO 3: INICIANDO CONTENEDORES" -ForegroundColor Cyan
docker-compose up -d

if ($LASTEXITCODE -eq 0) {
    Write-Host "Contenedores iniciados exitosamente" -ForegroundColor Green
} else {
    Write-Host "ERROR iniciando los contenedores" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "PASO 4: ESPERANDO CONFIGURACION DE BASE DE DATOS..." -ForegroundColor Cyan
Write-Host "El backend ejecutara automaticamente migraciones y seeders" -ForegroundColor Yellow

Start-Sleep -Seconds 30

Write-Host ""
Write-Host "ESTADO DE LOS CONTENEDORES" -ForegroundColor Cyan
docker-compose ps

Write-Host ""
Write-Host "====== APLICACION LISTA ======" -ForegroundColor Green
Write-Host "Frontend (React): http://localhost:5173" -ForegroundColor White
Write-Host "Backend (Laravel): http://localhost:3000" -ForegroundColor White
Write-Host "Base de datos (MySQL): localhost:3307" -ForegroundColor White
Write-Host ""
Write-Host "Para ver los logs: docker-compose logs -f" -ForegroundColor Gray
Write-Host "Para detener: docker-compose down" -ForegroundColor Gray
