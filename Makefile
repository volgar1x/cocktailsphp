PHP = $(shell which php)

.PHONY: default setup run

default: run

run:
	$(PHP) -S 0.0.0.0:3000

setup:
	createdb cocktailsphp
	psql cocktailsphp < cours/cocktails.sql

setdown:
	dropdb cocktailsphp
