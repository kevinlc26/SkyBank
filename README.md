# SkyBank

## Guía de instalación y ejecución

Esta guía te ayudará a configurar y ejecutar SkyBank en tu entorno local.

### Requisitos previos

Antes de comenzar, asegúrate de tener instalados y configurados los siguientes programas:

* **XAMPP:** Necesario para el servidor web Apache y la base de datos MySQL.
* **Node.js y npm (Node Package Manager):
* **Git:** Utilizado para clonar el repositorio del proyecto.

### Paso 1: Clonar el repositorio

1.  Abre tu terminal o línea de comandos.
2.  Navega hasta la carpeta `htdocs` de tu instalación de XAMPP. La ruta típica es:
    * **Windows:** `C:\xampp\htdocs\`
3.  Ejecuta el siguiente comando para clonar el repositorio de SkyBank:
    ```bash
    git clone https://github.com/kevinlc26/SkyBank.git
    cd SkyBank

### Paso 2: Configurar la base de datos

1.  Inicia XAMPP desde su panel de control. Asegúrate de que los módulos de **Apache** y **MySQL** estén en funcionamiento (sus indicadores deben estar en verde).
2.  Abre tu navegador web y accede a la interfaz de administración de phpMyAdmin. La URL por defecto suele ser:
    ```
    http://localhost/phpmyadmin
    ```
3.  En phpMyAdmin, crea una nueva base de datos. Haz clic en el botón "Nueva" o busca la opción para crear una base de datos. Asigna el nombre que prefieras para tu base de datos (por ejemplo, `skybank_db`).
4.  Selecciona la base de datos que acabas de crear en la lista de la izquierda.
5.  Ve a la pestaña "Importar".
6.  Haz clic en el botón "Seleccionar archivo" y navega hasta la ubicación del script SQL de la base de datos dentro de tu proyecto. La ruta debería ser:
    ```
    includes/script_inicial.sql
    ```
7.  Selecciona el archivo `script_inicial.sql` y haz clic en el botón "Continuar" o "Importar" para ejecutar el script. Esto creará las tablas y los datos iniciales necesarios para SkyBank.

### Paso 3: Instalar dependencias y ejecutar la aplicación (si aplica)

Si tu proyecto SkyBank tiene un frontend construido con Node.js, sigue estos pasos. 

1.  Abre una nueva terminal o ventana de línea de comandos.
2.  Navega hasta la carpeta del proyecto SkyBank que clonaste en el Paso 1:
    ```bash
    cd SkyBank
    ```
3.  Ejecuta el siguiente comando para instalar todas las dependencias del frontend:
    ```bash
    npm install
    ```
4.  Una vez que las dependencias se hayan instalado correctamente, ejecuta el comando para iniciar el servidor de desarrollo del frontend 
    * **Vue (con Vite):** `npm run dev`

### Paso 4: Acceder a la aplicación

1.  La terminal te proporcionará una URL para acceder a la aplicación. Generalmente, esta URL será:
    ```
    http://localhost:5173
    ```
