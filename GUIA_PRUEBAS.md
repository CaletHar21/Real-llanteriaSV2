# 🧪 Guía de Pruebas - Llantería SV2

## ✅ Estado del Sistema

- ✅ **Backend Laravel**: http://localhost:3000 (Puerto 3000)
- ✅ **Frontend React**: http://localhost:5173 (Puerto 5173)
- ✅ **Base de Datos MySQL**: Puerto 3307 (interna 3306)
- ✅ **Docker**: 3 contenedores activos (db, backend, frontend)

---

## 🔐 Credenciales de Prueba

### Usuarios Disponibles (todos con contraseña: `123456789`)

#### Administrador
- **Email**: `caleth.torrez17@itca.edu.sv`
- **Rol**: ADMIN
- **Acceso**: Panel administrativo completo

#### Usuario Regular (Comprador)
- **Email**: `usuario1@llanteria.com`
- **Rol**: usuario
- **Acceso**: Comprar, mis pedidos, asistencia vial

#### Conductor
- **Email**: `conductor1@llanteria.com`
- **Rol**: conductor
- **Acceso**: Ver entregas asignadas

#### Mecánico
- **Email**: `mecanico1@llanteria.com`
- **Rol**: mecanico
- **Acceso**: Ver asistencias asignadas

---

## 🧪 Pruebas Básicas

### 1️⃣ **Acceso al Frontend**
```
URL: http://localhost:5173
Resultado esperado: Página principal carga correctamente
```

### 2️⃣ **Registro de Nuevo Usuario**
```
Ruta: http://localhost:5173/register
- Llenar formulario con datos
- Hacer clic en "Registrarse"
Resultado esperado: Usuario creado y redirigido a login
```

### 3️⃣ **Login**
```
Ruta: http://localhost:5173/login
Email: caleth.torrez17@itca.edu.sv
Contraseña: 123456789
Resultado esperado: Login exitoso, redirección a dashboard o home
```

### 4️⃣ **Ver Catálogo de Llantas**
```
Ruta: http://localhost:5173/llantas
Resultado esperado: Se cargan 15 llantas de prueba con imágenes
```

### 5️⃣ **Panel Administrativo (Admin)**
```
Ruta: http://localhost:5173/admin/dashboard
Requisito: Estar logueado como admin
Resultado esperado: Dashboard con estadísticas

Opciones disponibles en sidebar:
- Dashboard (estadísticas)
- Gestión de Llantas
- Gestión de Usuarios
- Gestión de Clientes
- Pedidos
- Entregas
- Asistencia Vial
- Reportes
```

---

## 🔌 Pruebas API (con Postman o curl)

### Autenticación (Obtener Token)
```bash
POST http://localhost:3000/api/login
Content-Type: application/json

{
  "email": "caleth.torrez17@itca.edu.sv",
  "password": "123456789"
}

Resultado: 
{
  "user": {...},
  "token": "1|xxxxx..." 
}
```

### Obtener Lista de Pedidos (Admin)
```bash
GET http://localhost:3000/api/pedidos/admin/todos
Authorization: Bearer {TOKEN}

Resultado esperado: Array de 8 pedidos creados
```

### Obtener Lista de Entregas
```bash
GET http://localhost:3000/api/entregas/admin/todas
Authorization: Bearer {TOKEN}

Resultado esperado: Array de 6 entregas
```

### Obtener Lista de Asistencias Viales
```bash
GET http://localhost:3000/api/asistencia-vial/admin/todas
Authorization: Bearer {TOKEN}

Resultado esperado: Array de 7 asistencias
```

### Obtener Llantas
```bash
GET http://localhost:3000/api/llantas
Authorization: Bearer {TOKEN}

Resultado esperado: 15 llantas con imágenes
```

---

## 📊 Datos de Prueba Disponibles

### Base de Datos
- **10+ Usuarios** con diferentes roles
- **15 Llantas** con imágenes funcionales
- **10 Marcas de Vehículos** (Toyota, Honda, Ford, etc.)
- **8 Pedidos** con estados variados
- **6 Entregas** asignadas
- **7 Asistencias Viales** registradas

---

## ⚠️ Limitaciones Actuales

❌ **No Implementado Aún:**
- Compra de llantas (carrito)
- Sistema de pago
- CRUD completo de llantas en admin (solo lectura)
- CRUD completo de usuarios en admin (solo lectura parcial)
- Notificaciones en tiempo real
- Dashboard de conductor/mecánico

✅ **Funcional:**
- Login/Logout
- Ver catálogo de llantas
- Panel administrativo (visualización)
- Cambiar estado de pedidos/entregas
- Asignar mecánicos a asistencias
- Ver reportes y estadísticas

---

## 🐛 Troubleshooting

### Si el frontend no carga
```bash
# Reiniciar contenedor frontend
docker-compose -f docker-compose.yml restart frontend
```

### Si el backend da error
```bash
# Ver logs
docker-compose -f docker-compose.yml logs backend

# Reiniciar
docker-compose -f docker-compose.yml restart backend
```

### Si hay problemas de CORS
- Verificar que el frontend esté en http://localhost:5173
- Verificar que el backend esté en http://localhost:3000
- Los headers CORS están configurados en `app/Http/Middleware/CorsMiddleware.php`

### Si la BD tiene problemas
```bash
# Resetear BD completamente
docker-compose -f docker-compose.yml exec -T backend php artisan migrate:fresh --seed

# O solo reejecutar seeders
docker-compose -f docker-compose.yml exec -T backend php artisan db:seed
```

---

## 📝 Checklist de Pruebas

- [ ] Backend responde en puerto 3000
- [ ] Frontend carga en puerto 5173
- [ ] Login funciona correctamente
- [ ] Catálogo de llantas muestra 15 productos
- [ ] Panel admin carga sin errores
- [ ] API retorna pedidos, entregas y asistencias
- [ ] Cambio de estado de pedidos funciona
- [ ] Asignación de mecánicos funciona
- [ ] Reportes se generan correctamente

---

## 🚀 Próximos Pasos

1. Implementar carrito de compras
2. Crear formulario de checkout
3. Integrar sistema de pagos
4. CRUD completo de llantas
5. Dashboard para conductores
6. Dashboard para mecánicos
7. Notificaciones en tiempo real

