DC := docker compose
CONTAINER_NAME := thrivecart_test_web

up:
	$(DC) up -d

down:
	$(DC) down

build:
	$(DC) build

in:
	docker exec -it $(CONTAINER_NAME) bash
