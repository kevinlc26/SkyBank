# SkyBank
## Instalación

Para ejecutar SkyBank en tu entorno local, sigue estos pasos:

1.  **Descarga el proyecto:** Descarga el repositorio de SkyBank desde GitHub a la ubicación que prefieras en tu sistema.

2.  **Mueve la carpeta del proyecto a la carpeta `htdocs` de XAMPP:** Una vez descargado, copia o mueve la carpeta completa de SkyBank (`SkyBank`) dentro del directorio `htdocs` de tu instalación de XAMPP. La ruta típica sería algo como `C:\xampp\htdocs\` en Windows o `/opt/lampp/htdocs/` en Linux.

3.  **Crea la base de datos:**
    * Abre tu navegador y accede al panel de administración de phpMyAdmin.
    * Crea una nueva base de datos. Con el nombre 'skybank'.

4.  **Importa el script de la base de datos:**
    * Selecciona la base de datos que acabas de crear.
    * Ve a la pestaña "Importar".
    * Haz clic en el botón "Seleccionar archivo" y navega hasta la ubicación del script de la base de datos. Este archivo se encuentra dentro de la carpeta de tu proyecto en la siguiente ruta: `/includes/script_inicial.sql`.
    * Selecciona el archivo `script_inicial.sql` y haz clic en el botón "Continuar" o "Importar". Esto ejecutará el script y creará las tablas y los datos iniciales necesarios para SkyBank.

