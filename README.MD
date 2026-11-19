# PetCare - Sistema de Gestión Veterinaria

## Trabajo Práctico N°6 - Integrador

Sistema web desarrollado en PHP para la gestión de una veterinaria, con control de clientes, mascotas, veterinarios y consultas médicas.

---

## 📋 Características Principales

### 🔐 Autenticación y Autorización
 - **Login/Registro** de usuarios con contraseñas hasheadas (bcrypt)
 - **Sesiones** seguras con PHP
 - **Control de acceso por roles**:
   - **Admin**: Acceso total al sistema (CRUD completo, administración de usuarios)
   - **Veterinario**: Gestión de veterinarios y consultas médicas
   - **Usuario común**: Gestión de clientes y mascotas
 - **Cambio de contraseña** para usuarios autenticados

### 👥 Gestión de Entidades (CRUD Completo)
 - **Clientes**: DNI, nombre, apellido, teléfono
 - **Mascotas**: Nombre, especie, raza, fecha de nacimiento, sexo, cliente dueño
 - **Veterinarios**: DNI, nombre, apellido, número de registro, teléfono, especialidad
 - **Consultas**: Fecha/hora, motivo, diagnóstico, precio, mascota, veterinario

### 🎨 Interfaz de Usuario
 - **Bootstrap 5.3** para diseño responsivo y moderno
 - **Bootstrap Icons** para iconografía
 - **Dashboard** con estadísticas y accesos rápidos
 - **Tablas** con búsqueda, paginación y acciones
 - **Modales** para crear/editar/eliminar sin salir de la lista
 - **Validación** tanto cliente como servidor
 - **Mensajes flash** para feedback al usuario

### 🗄️ Base de Datos
 - **MySQL/MariaDB** con claves foráneas e integridad referencial
 - **Script SQL** completo en `/database/petcare.sql`
 - **Datos de prueba** incluidos
 - **Vistas** útiles para consultas complejas
 - **Índices** para optimización

---

## 🚀 Instalación en XAMPP (Local)

### 1. Requisitos Previos
 - **XAMPP** instalado (Apache + MySQL)
 - **PHP 8.0+** (incluido en XAMPP)

### 2. Clonar/Copiar el Proyecto
```bash
# Copiar la carpeta 'petcare' a tu directorio htdocs de XAMPP
# Ejemplo: C:\xampp\htdocs\petcare
```

### 3. Crear la Base de Datos
1. Iniciar **Apache** y **MySQL** desde el panel de control de XAMPP
2. Abrir **phpMyAdmin** (http://localhost/phpmyadmin)
3. Importar el archivo `database/petcare.sql`
   - O ejecutar el SQL directamente en la pestaña SQL

### 4. Configurar la Conexión (si es necesario)
Editar `includes/config.php` si tus credenciales de MySQL son diferentes:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'petcare');
define('DB_USER', 'root');
define('DB_PASS', '');  // En XAMPP por defecto no hay contraseña
```

### 5. Acceder al Sistema
Abrir en el navegador: **http://localhost/petcare/**

### 6. Credenciales de Prueba
| Usuario | Email | Contraseña | Rol |
|---------|-------|------------|-----|
| Admin | admin@petcare.com | admin123 | Admin |

> **Nota**: Puedes registrar nuevos usuarios desde la interfaz (rol por defecto: "Usuario común") o desde el panel de administración (solo Admin).

---

## ☁️ Despliegue en InfinityFree (Hosting Gratuito)

### 1. Preparar Archivos
1. Comprimir toda la carpeta `petcare` en un archivo `.zip`
2. **Importante**: Modificar `includes/config.php` con los datos de InfinityFree:

```php
// Configuración para InfinityFree
define('DB_HOST', 'sqlXXX.infinityfree.com');  // Tu host de BD
define('DB_NAME', 'if0_XXXXXXXX_petcare');     // Tu nombre de BD
define('DB_USER', 'if0_XXXXXXXX');             // Tu usuario de BD
define('DB_PASS', 'tu_password');              // Tu contraseña de BD

// URL de producción
define('APP_URL', 'https://tudominio.infinityfreeapp.com');
```

### 2. Subir a InfinityFree
1. Acceder al **File Manager** de InfinityFree
2. Subir el `.zip` a la carpeta `htdocs`
3. Extraer el archivo
4. Verificar que la estructura quede:
   ```
   htdocs/
   ├── index.php
   ├── includes/
   ├── pages/
   ├── assets/
   └── database/
   ```

### 3. Crear Base de Datos en InfinityFree
1. En el panel de InfinityFree → **MySQL Databases**
2. Crear nueva base de datos (ej: `if0_XXXXXXXX_petcare`)
3. Anotar: **Host**, **Nombre BD**, **Usuario**, **Contraseña**
4. Entrar a **phpMyAdmin** de InfinityFree
5. Importar `database/petcare.sql`

### 4. Configurar config.php
Editar el archivo `includes/config.php` en el servidor con los datos de tu BD de InfinityFree (ver paso 1).

### 5. Verificar Funcionamiento
Acceder a tu URL de InfinityFree (ej: `https://tudominio.infinityfreeapp.com`)

> **Importante**: InfinityFree tiene limitaciones (ej. `max_execution_time`, `memory_limit`). El sistema está optimizado para funcionar dentro de esos límites.

---

## 📁 Estructura del Proyecto

```
petcare/
├── index.php                 # Dashboard principal
├── README.md                 # Este archivo
├── includes/                 # Archivos compartidos
│   ├── config.php           # Configuración BD y app
│   ├── db.php               # Conexión PDO (Singleton)
│   ├── auth.php             # Funciones auth/roles
│   ├── functions.php        # CRUD de todas las entidades
│   ├── header.php           # Header + navbar dinámico
│   └── footer.php           # Footer + scripts
├── pages/
│   ├── auth/                # Login, registro, logout, perfil, password
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── logout.php
│   │   ├── perfil.php
│   │   └── cambiar_password.php
│   ├── cliente/             # CRUD Clientes
│   │   ├── listar.php
│   │   ├── crear.php
│   │   ├── editar.php
│   │   ├── ver.php
│   │   └── eliminar.php
│   ├── mascota/             # CRUD Mascotas
│   │   ├── listar.php
│   │   ├── crear.php
│   │   ├── editar.php
│   │   ├── ver.php
│   │   └── eliminar.php
│   ├── veterinario/         # CRUD Veterinarios
│   │   ├── listar.php
│   │   ├── crear.php
│   │   ├── editar.php
│   │   ├── ver.php
│   │   └── eliminar.php
│   ├── consulta/            # CRUD Consultas
│   │   ├── listar.php
│   │   ├── crear.php
│   │   ├── editar.php
│   │   ├── ver.php
│   │   └── eliminar.php
│   └── admin/               # Panel Admin
│       └── usuarios.php     # Gestión de usuarios
├── assets/
│   ├── css/
│   │   └── style.css        # Estilos personalizados
│   └── js/
│       └── main.js          # JavaScript principal
└── database/
    └── petcare.sql          # Script completo de BD
```

---

## 🔧 Tecnologías Utilizadas

| Tecnología | Versión | Uso |
|------------|---------|-----|
| PHP | 8.0+ | Backend |
| MySQL/MariaDB | 5.7+/10.3+ | Base de datos |
| Bootstrap | 5.3.2 | Frontend framework |
| Bootstrap Icons | 1.11.1 | Iconografía |
| PDO | - | Acceso a BD seguro |
| Sessions | - | Manejo de estado |
| Password Hashing | bcrypt | Seguridad contraseñas |

---

## 🔒 Seguridad Implementada

 - **Prepared Statements (PDO)** para prevenir SQL Injection
 - **Password Hashing** con `password_hash()` (bcrypt)
 - **Validación** tanto cliente (HTML5) como servidor (PHP)
 - **Control de Sesiones** seguras
 - **Control de Acceso por Roles** (RBAC)
 - **Escape de Salida** con `htmlspecialchars()` para prevenir XSS
 - **CSRF Protection** básico (validación de origen en formularios críticos)

---

## 📝 Usuarios de Prueba Incluidos

### Base de Datos (clientes, mascotas, veterinarios, consultas)
Ver `database/petcare.sql` para datos completos.

### Usuarios del Sistema
| Email | Contraseña | Rol |
|-------|------------|-----|
| admin@petcare.com | admin123 | Admin |

> Puedes crear más usuarios desde **Administración → Usuarios** (solo Admin) o desde **Registrarse** (rol: Usuario común).

---

## 🎯 Cumplimiento del TP6 Integrador

✅ **Conexión a BD** - PDO con Singleton pattern  
✅ **ABM (CRUD)** - Completo para 4 entidades + usuarios  
✅ **Registro/Login** - Con validación y hash de contraseñas  
✅ **Sesiones** - Manejo seguro con PHP sessions  
✅ **Cambio de Contraseña** - Verificación de contraseña actual  
✅ **Control de Acceso por Roles** - Admin, Veterinario, Usuario común  
✅ **Script BD** - En `/database/petcare.sql`  
✅ **Usuarios de Prueba** - Incluidos en el SQL  
✅ **README.md** - Este archivo con documentación completa  
✅ **Despliegue InfinityFree** - Instrucciones incluidas  

---

## 🐛 Solución de Problemas Comunes

### Error de Conexión a BD
```
Error: SQLSTATE[HY000] [1045] Access denied for user...
```
→ Verificar credenciales en `includes/config.php`

### Página en Blanco / Error 500
→ Revisar `error_log` de Apache o habilitar `display_errors` en `php.ini` temporalmente

### Estilos no Cargan (InfinityFree)
→ Verificar que `APP_URL` en `config.php` coincida con tu URL real (http vs https)

### Sesiones no Funcionan
→ Verificar que `session.save_path` esté configurado y sea escribible

---

## 📄 Licencia

Proyecto educativo - Trabajo Práctico N°6 Integrador

---

## 👨‍💻 Autor

Desarrollado para el curso de Programación Web / Bases de Datos

---

**¡Listo para usar!** 🎉

Cualquier duda o sugerencia, revisar el código en `includes/functions.php` para ver todas las funciones disponibles.
