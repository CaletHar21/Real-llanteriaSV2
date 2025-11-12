# 🐳 Guía Docker - Llantería SV2

## Prerequisitos

1. **Docker Desktop** debe estar instalado y ejecutándose
2. **PowerShell** (viene con Windows)

## 🚀 Inicio Rápido

### Opción 1: Script Automático
```powershell
# Ejecutar desde el directorio raíz del proyecto
.\build-and-run.ps1
```

### Opción 2: Script Interactivo
```powershell
# Para un menú de opciones
.\docker-helper.ps1
```

### Opción 3: Comandos Manuales
```powershell
# Construir y ejecutar
docker-compose build
docker-compose up -d

# Ver estado
docker-compose ps

# Ver logs
docker-compose logs -f
```

## 📋 Servicios Incluidos

| Servicio | Puerto | URL | Descripción |
|----------|---------|-----|-------------|
| Frontend | 5173 | http://localhost:5173 | Aplicación React |
| Backend | 3000 | http://localhost:3000 | API Laravel |
| Base de Datos | 3307 | localhost:3307 | MySQL |

## ⚙️ Variables de Entorno

Las variables están definidas en `.env`:

```env
# Base de datos
DB_HOST=db
DB_PORT=3306
DB_NAME=llanteria
DB_USER=caleth
DB_PASSWORD=calethpass

# Puertos externos
BACKEND_PORT=3000
FRONTEND_PORT=5173
DB_PORT=3307
```

## 📝 Comandos Útiles

### Gestión de Contenedores
```powershell
# Iniciar servicios
docker-compose up -d

# Detener servicios
docker-compose down

# Ver logs en tiempo real
docker-compose logs -f

# Ver estado
docker-compose ps

# Reconstruir sin cache
docker-compose build --no-cache
```

### Acceso a Contenedores
```powershell
# Entrar al backend
docker exec -it llanteria-sv2-backend bash

# Entrar al frontend
docker exec -it llanteria-sv2-frontend sh

# Entrar a la base de datos
docker exec -it llanteria-sv2-db mysql -u caleth -p
```

### Comandos Laravel
```powershell
# Ejecutar migraciones
docker exec -it llanteria-sv2-backend php artisan migrate

# Ejecutar seeders
docker exec -it llanteria-sv2-backend php artisan db:seed

# Limpiar cache
docker exec -it llanteria-sv2-backend php artisan cache:clear

# Generar clave de aplicación
docker exec -it llanteria-sv2-backend php artisan key:generate
```

## 🔧 Solución de Problemas

### Docker no inicia
```powershell
# Verificar si Docker está ejecutándose
docker ps

# Si no funciona, iniciar Docker Desktop manualmente
```

### Puerto ocupado
```powershell
# Ver qué está usando el puerto
netstat -ano | findstr :5173
netstat -ano | findstr :3000

# Cambiar puertos en .env si es necesario
```

### Problemas de base de datos
```powershell
# Resetear base de datos
docker exec -it llanteria-sv2-backend php artisan migrate:fresh --seed

# Verificar conexión
docker exec -it llanteria-sv2-db mysql -u caleth -p -e "SHOW DATABASES;"
```

### Limpiar todo y empezar de nuevo
```powershell
# Detener y eliminar todo
docker-compose down -v --rmi all

# Reconstruir desde cero
docker-compose build --no-cache
docker-compose up -d
```

## 📁 Estructura de Archivos Docker

```
llanteria-sv2/
├── docker-compose.yml      # Configuración principal
├── .env                   # Variables de entorno
├── build-and-run.ps1     # Script automático
├── docker-helper.ps1     # Script interactivo
├── backend-laravel/
│   └── Dockerfile        # Imagen PHP/Laravel
└── frontend-react/
    └── Dockerfile        # Imagen Node.js/React
```

## 🔄 Flujo de Desarrollo

1. **Hacer cambios en el código**
2. **Los cambios se reflejan automáticamente** (gracias a los volumes)
3. **Para cambios en dependencias:**
   ```powershell
   docker-compose restart frontend  # Para npm install
   docker-compose restart backend   # Para composer install
   ```

## 🎯 URLs de Desarrollo

- **Frontend**: http://localhost:5173
- **Backend API**: http://localhost:3000
- **Base de datos**: 
  - Host: localhost
  - Puerto: 3307
  - Usuario: caleth
  - Contraseña: calethpass
  - Base de datos: llanteria

## ⚠️ Notas Importantes

- Los volúmenes mantienen los datos persistentes
- Los cambios en código se reflejan inmediatamente
- Para cambios en Dockerfile, usar `docker-compose build`
- Los logs se pueden ver con `docker-compose logs -f`