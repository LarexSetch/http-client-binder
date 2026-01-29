.PHONY: test

test:
	docker compose run --build --rm -v .:/opt/project php vendor/bin/phpunit --display-deprecations; \
	docker compose down -v;
bash:
	docker compose run --build --rm -v .:/opt/project php bash; \
	docker compose down -v;
