# Nombre del archivo: Makefile

# Definir el nombre del archivo de docker-compose
DOCKER_COMPOSE_FILE=docker-compose.yml

# Objetivos disponibles en el Makefile
.PHONY: build up start stop down restart logs composer-install key-generate migrate create-testing-db migrate-testing-db setup

# Construir los servicios definidos en el archivo docker-compose
build:
	docker-compose -f $(DOCKER_COMPOSE_FILE) build

# Iniciar los servicios en modo demonio (background)
up:
	docker-compose -f $(DOCKER_COMPOSE_FILE) up -d

# Detener los servicios sin eliminar los contenedores
stop:
	docker-compose -f $(DOCKER_COMPOSE_FILE) stop

# Iniciar los servicios que ya están creados (sin reconstruir)
start:
	docker-compose -f $(DOCKER_COMPOSE_FILE) start

# Detener y eliminar los servicios y redes
down:
	docker-compose -f $(DOCKER_COMPOSE_FILE) down

# Reiniciar los servicios
restart:
	docker-compose -f $(DOCKER_COMPOSE_FILE) restart

# Ver los logs de los servicios
logs:
	docker-compose -f $(DOCKER_COMPOSE_FILE) logs -f

# Instalar dependencias de composer en el contenedor app
composer-install:
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec app composer install

# Generar la clave de Laravel dentro del contenedor app
key-generate:
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec app php artisan key:generate

# Ejecutar las migraciones de Laravel dentro del contenedor app
migrate:
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec app php artisan migrate

# Crear la base de datos de prueba en el contenedor mariadb
create-testing-db:
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec mariadb mysql -u root -pqweasd123 -e "CREATE DATABASE IF NOT EXISTS laravel_testing;"

# Ejecutar migraciones en la base de datos de pruebas
migrate-testing-db:
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec app php artisan migrate --database=testing_db

# Limpiar caché y configuración dentro del contenedor app
cache-clear:
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec app php artisan config:clear
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec app php artisan cache:clear
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec app php artisan config:cache

# Limpiar datos de volúmenes y reiniciar desde cero
clean:
	docker-compose down -v
	sudo chown -R $$(whoami):$$(whoami) ./docker/data/mariadb ./docker/data/redis
	sudo chmod -R u+rwX ./docker/data/mariadb ./docker/data/redis
	sudo rm -rf ./docker/data/mariadb
	sudo rm -rf ./docker/data/redis

# Ejecutar todos los tests de PHPUnit dentro del contenedor app
test:
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec app ./vendor/bin/phpunit

# Generar la clave secreta JWT dentro del contenedor app
jwt-secret:
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec app php artisan jwt:secret

# Ejecutar el seeder UserRolePermissionSeeder dentro del contenedor app
db-seed-user-role-permission:
	docker-compose -f $(DOCKER_COMPOSE_FILE) exec app php artisan db:seed --class=UserRolePermissionSeeder
