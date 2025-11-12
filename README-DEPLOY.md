# 🚗 Real Llantería SV2 - Sistema Completo

Sistema completo de gestión de llanterías con React, Laravel y MySQL desplegado en Railway.

## 🚀 Despliegue en Railway - 3 Servicios

Este repositorio está **optimizado para Railway** con 3 servicios separados:

### 📋 **Servicios:**
1. **🔧 Backend (Laravel)** - `./backend-laravel`
2. **🎨 Frontend (React)** - `./frontend-react` 
3. **🗄️ Database (MySQL)** - Plugin de Railway

---

## 🎯 **Deploy en Railway - Paso a Paso**

### **1. Crear Proyecto**
- Ir a: https://railway.app/dashboard
- **New Project** → **Empty Project**
- Nombre: `real-llanteria-sv2`

### **2. Crear Base de Datos**
- **+ Add Plugin** → **MySQL**
- ✅ Anota las credenciales para el backend

### **3. Crear Backend**
- **+ Add Service** → **GitHub Repo** 
- **Repo:** `CaletHar21/Real-llanteriaSV2`
- **Root Directory:** `backend-laravel`

**Variables de entorno Backend:**
```env
APP_NAME=Real Llanteria SV2
APP_ENV=production
APP_KEY=base64:qmodJdjUv6itM3dPn0Pr/rRJ0LL0+dNtGCeJJDycyTQ=
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}
DB_PORT=3306
CORS_ALLOWED_ORIGINS=${{frontend.RAILWAY_PUBLIC_DOMAIN}}
```

### **4. Crear Frontend**
- **+ Add Service** → **GitHub Repo** (mismo repo)
- **Root Directory:** `frontend-react`

**Variables de entorno Frontend:**
```env
NODE_ENV=production
VITE_API_URL=${{backend.RAILWAY_PUBLIC_DOMAIN}}
```

---

## ✅ **URLs Finales**
- **Frontend:** `https://frontend-production-xxxx.up.railway.app`
- **Backend API:** `https://backend-production-xxxx.up.railway.app`
- **Health Check:** `https://backend-production-xxxx.up.railway.app/api/health`

---

## 🎮 **Demo y Login**
**Usuario de prueba:**
- **Email:** `caleth.torrez17@itca.edu.sv`
- **Password:** `123456789`

---

## 📊 **Features Incluidas**

### **🔧 Backend (Laravel 11)**
- ✅ API REST completa
- ✅ Autenticación con Sanctum
- ✅ Sistema de cotizaciones
- ✅ Gestión de inventario
- ✅ 17 llantas pre-cargadas con imágenes reales
- ✅ Health check endpoint
- ✅ Migraciones automáticas en deploy

### **🎨 Frontend (React + Vite)**
- ✅ Interfaz moderna y responsiva
- ✅ Autenticación completa
- ✅ Catálogo de llantas con imágenes
- ✅ Sistema de cotizaciones
- ✅ Dashboard de usuarios
- ✅ Build optimizado para producción

### **🗄️ Base de Datos (MySQL)**
- ✅ 8 marcas principales (Michelin, Bridgestone, Pirelli, etc.)
- ✅ 17 llantas con precios reales
- ✅ 3 usuarios de prueba (Admin, Agente, Cliente)
- ✅ Sistema completo de relaciones

---

## 🛠️ **Desarrollo Local**

```bash
# Clonar repo
git clone https://github.com/CaletHar21/Real-llanteriaSV2.git
cd Real-llanteriaSV2

# Opción 1: Docker Compose (Recomendado)
docker-compose up -d

# Opción 2: Manual
# Backend
cd backend-laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve

# Frontend (otra terminal)
cd frontend-react
npm install
npm run dev
```

---

## 📋 **Estructura del Proyecto**

```
Real-llanteriaSV2/
├── backend-laravel/          # API Laravel
│   ├── Dockerfile           # Optimizado para Railway
│   ├── railway.toml         # Configuración Railway
│   └── app/                 # Código Laravel
├── frontend-react/          # App React
│   ├── Dockerfile           # Build multi-stage
│   ├── railway.toml         # Configuración Railway  
│   └── src/                 # Código React
├── docker-compose.yml       # Para desarrollo local
└── README.md               # Esta documentación
```

---

## 🎯 **Tech Stack**

- **Frontend:** React 18 + Vite + Tailwind CSS
- **Backend:** Laravel 11 + MySQL + Sanctum
- **Deploy:** Railway (3 servicios)
- **Dev:** Docker Compose

---

## 📞 **Soporte**

Si tienes problemas con el deploy:
1. Verificar logs en Railway dashboard
2. Revisar health check: `/api/health`
3. Confirmar variables de entorno

---

**🚀 ¡Listo para producción en Railway!**