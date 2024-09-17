
# Guía de Configuración del Proyecto

Este documento proporciona una guía paso a paso para levantar y configurar el proyecto en tu máquina local. Asegúrate de tener [Docker](https://www.docker.com/) y [Make](https://www.gnu.org/software/make/) instalados en tu sistema antes de comenzar.

## Pasos para Levantar el Proyecto

Sigue estos pasos en el orden indicado para levantar el proyecto:

### 1. Construir las imágenes de Docker

Ejecuta el siguiente comando para construir las imágenes de Docker necesarias para el proyecto:

Antes de comenzar, asegúrate de copiar los valores del archivo .env.example a tu archivo .env.

```bash
make build

2. Iniciar los contenedores
Levanta los contenedores en modo desatendido (background):
make up

 3. Instalar las dependencias de Composer
Instala las dependencias de Composer dentro del contenedor app:
make composer-install


 4. Generar la clave de aplicación de Laravel
Genera la clave de aplicación de Laravel dentro del contenedor app:
make key-generate

5. Generar la clave secreta JWT dentro del contenedor app, para poder crear un JWT valido dentro de la app. Se esa utilizando el paquete  tymon/jwt-auth 

make jwt-secret

6. Ejecutar las migraciones de la base de datos principal
Ejecuta las migraciones de Laravel en la base de datos principal dentro del contenedor app:
make migrate

7. Limpiar caché y configuración
Limpia y vuelve a cachear la configuración de Laravel dentro del contenedor app:

make cache-clear

8. Crear la base de datos de prueba
Crea la base de datos de prueba dentro del contenedor mariadb:

make create-testing-db

9. Limpiar caché y configuración nuevamente
Limpia y vuelve a cachear la configuración de Laravel dentro del contenedor app:
make cache-clear

10. Ejecutar migraciones en la base de datos de prueba
Ejecuta las migraciones en la base de datos de pruebas dentro del contenedor app:
make migrate-testing-db

11. Limpiar caché y configuración una vez más
Limpia y vuelve a cachear la configuración de Laravel dentro del contenedor app:
make cache-clear

12. Ejecutar todos los tests de PHPUnit
Ejecuta todos los tests de PHPUnit dentro del contenedor app para verificar el estado de la aplicación:
make test

Notas Adicionales
Asegúrate de que Docker y Make estén instalados en tu sistema.

Si encuentras problemas de permisos, puedes necesitar usar sudo para algunos comandos de make.

Revisa los logs de los contenedores para solucionar problemas específicos:

make logs
Para limpiar los datos de los volúmenes y reiniciar desde cero, utiliza el objetivo clean del Makefile:

make clean

Nota: Este comando eliminará los datos de las bases de datos y volúmenes asociados. Asegúrate de que esto es lo que deseas antes de ejecutarlo.





