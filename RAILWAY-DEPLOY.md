# 🚀 Guía de Despliegue en Railway - Llantería SV2

## 📋 Problema Original
Railway solo detectó **1 servicio** en lugar de los **3 contenedores** (backend, frontend, database) porque las PaaS no ejecutan `docker-compose.yml` automáticamente.

## ✅ Solución: 3 Servicios Separados en Railway

### 🎯 **Instrucciones Paso a Paso**

#### **1. Crear Nuevo Proyecto en Railway**
1. Ve a [Railway Dashboard](https://railway.app/dashboard)
2. **New Project** → **Empty Project**
3. Nombre: `llanteria-sv2-production`

#### **2. Crear Servicio Backend**
1. **Add Service** → **GitHub Repo** 
2. Seleccionar: `CaletHar21/llanteria-sv2`
3. **Service Name:** `backend`
4. **Source:** 
   - **Branch:** `railway-deploy`
   - **Root Directory:** `backend-laravel`

**Variables de Entorno Backend:**
```env
APP_NAME=Llanteria SV2
APP_ENV=production
APP_KEY=base64:[GENERAR_CON_ARTISAN]
APP_DEBUG=false
APP_URL=${{ RAILWAY_PUBLIC_DOMAIN }}

DB_CONNECTION=mysql
DB_HOST=${{ MySQL.MYSQL_PRIVATE_URL }}
DB_PORT=3306
DB_DATABASE=${{ MySQL.MYSQL_DATABASE }}
DB_USERNAME=${{ MySQL.MYSQL_USER }}
DB_PASSWORD=${{ MySQL.MYSQL_PASSWORD }}

CORS_ALLOWED_ORIGINS=${{ frontend.RAILWAY_PUBLIC_DOMAIN }}
SESSION_LIFETIME=120
```

#### **3. Crear Base de Datos**
1. **Add Plugin** → **MySQL**
2. ✅ Railway conecta automáticamente las variables

#### **4. Crear Servicio Frontend**
1. **Add Service** → **GitHub Repo** (mismo repo)
2. **Service Name:** `frontend`
3. **Source:**
   - **Branch:** `railway-deploy` 
   - **Root Directory:** `frontend-react`

**Variables de Entorno Frontend:**
```env
VITE_API_URL=${{ backend.RAILWAY_PUBLIC_DOMAIN }}
NODE_ENV=production
```

#### **5. Generar APP_KEY**
```bash
# En terminal local:
cd backend-laravel
php artisan key:generate --show
# Copiar resultado a Railway backend env vars
```

### 🔗 **URLs Finales**
- **Frontend:** `https://frontend-production-xxxx.up.railway.app`
- **Backend API:** `https://backend-production-xxxx.up.railway.app` 
- **Health Check:** `https://backend-production-xxxx.up.railway.app/api/health`

### ✅ **Verificación**
1. **Backend Health:** Visitar `/api/health` debe retornar JSON
2. **Frontend:** Login con `caleth.torrez17@itca.edu.sv` / `123456789`
3. **Database:** Backend ejecuta migraciones automáticamente

### 🐛 **Troubleshooting**
- **CORS Error:** Verificar `CORS_ALLOWED_ORIGINS` en backend
- **DB Error:** Esperar 1-2 minutos, MySQL tarda en inicializar
- **Build Error:** Verificar logs en Railway dashboard

---

## 🆚 **Alternativas**

### **Opción A: Railway (Esta rama)**
✅ Gestión automática de infraestructura  
✅ Escalamiento automático  
✅ SSL y dominios incluidos  
❌ Límites de plan gratuito  

### **Opción B: Master con Docker Compose**
✅ Funciona idéntico a desarrollo local  
✅ Un solo comando `docker-compose up -d`  
❌ Necesitas gestionar VPS manualmente  

### **Opción C: Render/Vercel**
✅ Similar a Railway pero diferentes límites  
✅ Buena integración con GitHub  
❌ Configuración similar (3 servicios separados)

---

**🚀 ¡Listo para desplegar en Railway!**