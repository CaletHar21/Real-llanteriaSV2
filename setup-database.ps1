# Script PowerShell para configurar la base de datos

Write-Host "CONFIGURANDO BASE DE DATOS AUTOMATICA" -ForegroundColor Green
Write-Host "============================================"

Write-Host "Resumen de datos que se insertaran:"
Write-Host "- Usuarios: Caleth y Cointra (contraseña: 1234566789)"
Write-Host "- 8 Marcas de llantas (Michelin, Bridgestone, Pirelli, etc.)"
Write-Host "- Modelos para cada marca"
Write-Host "- 3 Llantas de prueba"
Write-Host ""

Write-Host "Ejecutando migraciones y seeders..." -ForegroundColor Yellow

# Esperar que MySQL esté listo
Write-Host "Esperando que MySQL este disponible..." -ForegroundColor Yellow
Start-Sleep -Seconds 15

# Ejecutar migraciones
Write-Host "Paso 1: Ejecutando migraciones..." -ForegroundColor Cyan
$migrate = docker exec -it llanteria-sv2-backend php artisan migrate:fresh --force

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Migraciones completadas!" -ForegroundColor Green
    
    Write-Host "Paso 2: Ejecutando seeders..." -ForegroundColor Cyan
    $seed = docker exec -it llanteria-sv2-backend php artisan db:seed --force
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Datos insertados correctamente!" -ForegroundColor Green
        Write-Host ""
        Write-Host "BASE DE DATOS CONFIGURADA" -ForegroundColor Green
        Write-Host "=========================="
        Write-Host "Frontend: http://localhost:5173" -ForegroundColor White
        Write-Host "Backend: http://localhost:3000" -ForegroundColor White
        Write-Host "Base de datos: localhost:3307" -ForegroundColor White
        Write-Host ""
        Write-Host "Usuarios de prueba:" -ForegroundColor Yellow
        Write-Host "- caleth@llanteria.com (contraseña: 1234566789)" -ForegroundColor White
        Write-Host "- cointra@llanteria.com (contraseña: 1234566789)" -ForegroundColor White
        Write-Host ""
    } else {
        Write-Host "❌ Error en seeders" -ForegroundColor Red
    }
} else {
    Write-Host "❌ Error en migraciones" -ForegroundColor Red
}

Write-Host "Verificando estado de contenedores:" -ForegroundColor Cyan
docker-compose ps