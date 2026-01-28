.PHONY: test

test:
	docker compose run --build --rm -v .:/opt/project php vendor/bin/phpunit ; \
	docker compose down -v;
