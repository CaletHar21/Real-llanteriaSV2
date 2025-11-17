# Estructura de API - Usuarios y Clientes

## 1. GET /api/usuarios
**Retorna:** Lista paginada de TODOS los usuarios (12 registros)

```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "NOMBRES": "Admin",
      "APELLIDOS": "Sistema",
      "CORREO": "admin@test.com",
      "DUI": "00000000-0",
      "TELEFONO": "0000-0000",
      "DIRECCION": "Sistema",
      "ROL": "ADMIN",
      "created_at": "2025-11-16T16:02:42.000000Z",
      "updated_at": "2025-11-16T17:55:46.000000Z"
    },
    {
      "id": 2,
      "NOMBRES": "Caleth Abimael",
      "APELLIDOS": "Torrez Ramirez",
      "CORREO": "caleth.torrez17@itca.edu.sv",
      "DUI": "12345678-9",
      "TELEFONO": "1234566789",
      "DIRECCION": "San Salvador, El Salvador",
      "ROL": "ADMIN",
      "created_at": "2025-11-16T16:02:43.000000Z",
      "updated_at": "2025-11-16T17:55:47.000000Z"
    }
    // ... más registros
  ],
  "first_page_url": "http://localhost:3000/api/usuarios?page=1",
  "from": 1,
  "last_page": 1,
  "last_page_url": "http://localhost:3000/api/usuarios?page=1",
  "path": "http://localhost:3000/api/usuarios",
  "per_page": 15,
  "total": 12
}
```

**Campos principales de cada usuario:**
- `id`: número entero
- `NOMBRES`: string (nombre completo)
- `APELLIDOS`: string
- `CORREO`: string (email)
- `DUI`: string (documento de identidad)
- `TELEFONO`: string
- `DIRECCION`: string
- `ROL`: string (ADMIN, AGENTE, CLIENTE, usuario, conductor, mecanico)
- `created_at`: ISO datetime
- `updated_at`: ISO datetime

**Nota:** No incluye PASSWORD (está en `protected $hidden`)

---

## 2. GET /api/clientes
**Retorna:** Lista paginada de CLIENTES SOLAMENTE (usuarios con ROL='usuario' o ROL='CLIENTE')

```json
{
  "current_page": 1,
  "data": [
    {
      "id": 4,
      "NOMBRES": "Juan Carlos",
      "APELLIDOS": "Pérez López",
      "CORREO": "cliente@llanteria.com",
      "DUI": "11223344-5",
      "TELEFONO": "7890123456",
      "DIRECCION": "Soyapango, San Salvador",
      "ROL": "CLIENTE",
      "created_at": "2025-11-16T16:02:44.000000Z",
      "updated_at": "2025-11-16T17:55:48.000000Z"
    },
    {
      "id": 6,
      "NOMBRES": "María",
      "APELLIDOS": "Martínez García",
      "CORREO": "usuario1@llanteria.com",
      "DUI": "55667788-9",
      "TELEFONO": "7654321098",
      "DIRECCION": "Santa Tecla, El Salvador",
      "ROL": "usuario",
      "created_at": "2025-11-16T16:02:44.000000Z",
      "updated_at": "2025-11-16T17:55:48.000000Z"
    }
    // ... más registros
  ],
  "first_page_url": "http://localhost:3000/api/clientes?page=1",
  "from": 1,
  "last_page": 1,
  "last_page_url": "http://localhost:3000/api/clientes?page=1",
  "path": "http://localhost:3000/api/clientes",
  "per_page": 15,
  "total": 4
}
```

**Diferencia:** Solo retorna usuarios donde `ROL IN ('usuario', 'CLIENTE')`

---

## Resumen de Cambios Realizados

### Backend (Laravel):
✅ `UsuarioController@index` - Retorna `.toArray()` en lugar de objeto Paginator
✅ `ClienteController@index` - Retorna `.toArray()` en lugar de objeto Paginator  
✅ `PedidoController@all` - Retorna `.toArray()`
✅ `EntregaController@index` - Retorna `.toArray()`
✅ `EntregaController@all` - Retorna `.toArray()`
✅ `AsistenciaVialController@index` - Retorna `.toArray()`
✅ `AsistenciaVialController@all` - Retorna `.toArray()`

### Frontend (React):
✅ `useFetchData.js` hook - Extrae correctamente `response.data.data` para respuestas paginadas
✅ `AdminUsuarios.jsx` - Usa nombres de campos correctos (NOMBRES, CORREO, TELEFONO, ROL)
✅ `AdminClientes.jsx` - Usa nombres de campos correctos
✅ `AdminPedidos.jsx` - Usa nombres de campos correctos
✅ `AdminEntregas.jsx` - Usa nombres de campos correctos
✅ `AdminAsistencia.jsx` - Corregido filtrado de datos paginados

**Ahora recarga el navegador para que funcione correctamente!**
