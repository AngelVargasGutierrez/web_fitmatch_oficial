# FitMatch - Aplicación de Citas

FitMatch es una aplicación de citas tipo Tinder desarrollada en PHP con una interfaz moderna y funcionalidades completas.

## Características

- 🎨 **Interfaz moderna y responsiva** con animaciones CSS
- 👤 **Sistema de autenticación completo** (registro, login, logout)
- 💕 **Sistema de swipe** con drag & drop
- 📱 **Diseño mobile-first** con Bootstrap 5
- 🔒 **Sesiones seguras** con validación
- 📊 **Base de datos MySQL** optimizada
- 🎯 **Sistema de recomendaciones** inteligente

## Tecnologías Utilizadas

- **Backend**: PHP 7.4+
- **Base de Datos**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Herramientas**: HeidiSQL (gestión de base de datos)
- **Servidor**: XAMPP/Apache

## Instalación

### 1. Configurar XAMPP
1. Descarga e instala XAMPP desde [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Inicia Apache y MySQL desde el panel de control de XAMPP
3. Coloca el proyecto en la carpeta `htdocs` de XAMPP

### 2. Configurar Base de Datos MySQL
1. Abre HeidiSQL y conéctate a tu servidor MySQL local
2. Ejecuta el archivo `init_mysql.sql` para crear la base de datos y tablas
3. Verifica que la base de datos `fitmatch` se haya creado correctamente

### 3. Configurar la Aplicación
1. Edita el archivo `config/database.php` con tus credenciales de MySQL:
```php
'mysql' => [
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'fitmatch',
    'username' => 'root', // Tu usuario de MySQL
    'password' => '', // Tu contraseña de MySQL
    'charset' => 'utf8mb4'
]
```

### 4. Acceder a la Aplicación
1. Abre tu navegador y ve a `http://localhost/Fitmatch/public/`
2. La página de inicio debería cargar sin problemas
3. Puedes registrarte o hacer login para acceder a las funcionalidades

## Estructura del Proyecto

```
Fitmatch/
├── app/
│   ├── controllers/          # Controladores de la aplicación
│   │   ├── UserController.php
│   │   ├── SwipeController.php
│   │   └── ...
│   ├── models/              # Modelos de datos
│   └── views/               # Vistas de la aplicación
├── config/
│   └── database.php         # Configuración de base de datos
├── models/
│   ├── MySQLDB.php          # Clase de conexión MySQL
│   ├── UserRepository.php   # Repositorio de usuarios
│   └── ...
├── public/                  # Archivos públicos
│   ├── index.php           # Página de inicio
│   ├── login.php           # Página de login
│   ├── register.php        # Página de registro
│   ├── swipe.php           # Página de swipe
│   ├── my_profile.php      # Página de perfil
│   └── api/                # APIs REST
├── init_mysql.sql          # Script de creación de base de datos
└── README.md
```

## Base de Datos

### Tablas Principales

1. **users** - Información básica de usuarios
2. **profiles** - Información adicional de perfiles
3. **user_preferences** - Preferencias de búsqueda
4. **matches** - Matches entre usuarios
5. **swipes** - Likes/dislikes
6. **messages** - Mensajes entre usuarios
7. **reports** - Reportes de usuarios
8. **user_sessions** - Sesiones activas
9. **user_activity** - Actividad de usuarios

### Datos de Ejemplo
El script `init_mysql.sql` incluye 5 usuarios de ejemplo para probar la aplicación:
- maria@example.com / password
- carlos@example.com / password
- ana@example.com / password
- david@example.com / password
- laura@example.com / password

## Funcionalidades

### Página de Inicio (`/public/index.php`)
- Landing page moderna con animaciones
- Acceso público sin restricciones
- Navegación a login/registro

### Sistema de Autenticación
- **Registro**: Formulario completo con validación
- **Login**: Autenticación segura con sesiones
- **Perfil**: Edición de información personal
- **Logout**: Cierre de sesión seguro

### Sistema de Swipe (`/public/swipe.php`)
- Interfaz de swipe tipo Tinder
- Drag & drop para like/dislike
- Recomendaciones basadas en preferencias
- Almacenamiento de acciones en base de datos

### APIs REST
- `/api/user_api.php` - Gestión de usuarios
- `/api/swipe_api.php` - Acciones de swipe

## Configuración de HeidiSQL

1. **Conexión**:
   - Host: localhost
   - Puerto: 3306
   - Usuario: root
   - Contraseña: (la que configuraste en XAMPP)

2. **Crear Base de Datos**:
   - Ejecuta el archivo `init_mysql.sql`
   - Verifica que todas las tablas se crearon correctamente

3. **Verificar Datos**:
   - Revisa que los usuarios de ejemplo estén en la tabla `users`
   - Verifica que las preferencias estén en `user_preferences`

## Solución de Problemas

### Error de Conexión a Base de Datos
- Verifica que MySQL esté corriendo en XAMPP
- Confirma las credenciales en `config/database.php`
- Asegúrate de que la base de datos `fitmatch` existe

### Error de Sesión
- Verifica que las sesiones de PHP estén habilitadas
- Revisa los permisos de escritura en la carpeta temporal

### Página No Encontrada
- Confirma que Apache esté corriendo en XAMPP
- Verifica que el proyecto esté en la carpeta correcta de `htdocs`

## Desarrollo

### Agregar Nuevas Funcionalidades
1. Crea el controlador en `app/controllers/`
2. Crea el repositorio en `models/` si es necesario
3. Crea la vista en `public/`
4. Actualiza la base de datos si es necesario

### Estilo y Diseño
- Usa Bootstrap 5 para componentes
- Mantén la consistencia con el diseño actual
- Agrega animaciones CSS para mejor UX

## Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## Soporte

Para soporte técnico o preguntas, contacta al equipo de desarrollo. 