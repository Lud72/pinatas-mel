# Piñatas-Mel

## Instalación

### 1. Clonar el repositorio

Abre una terminal en la carpeta `htdocs` de XAMPP:

cd C:\xampp\htdocs
git clone https://github.com/Lud72/pinatas-mel/tree/version-pre

cd pinatas-mel

### 2. Crea la base de datos

Abre: http://localhost/phpmyadmin
Dirigite a la pestaña SQL 
Copia y Pega todo el contenido de SQL/pinatas_mel.sql
Clic en continuar

# Opcion Rapida:

C:\xampp\mysql\bin\mysql.exe -u root -p < sql/pinatas_mel.sql

Pega eso en una ternimal.

### USO:

Sitio público: http://localhost/pinatas-mel/

Login admin: http://localhost/pinatas-mel/paginas/admin/login.php

## Credenciales por defecto:

Usuario: admin

Contraseña: admin123

### Cambio de contraseña obligatorio:

Abre: http://localhost/pinatas-mel/generar_hash.php

EN PHPmyAdmin

UPDATE usuarios_admin
SET password_hash = 'PEGA_AQUI_EL_HASH' // Lo que te genere el anterior paso aprox. 60 caracteres.
WHERE usuario = 'admin';

Ejecuta esa setencia SQL.