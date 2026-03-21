UNAME := $(shell uname -s 2>/dev/null || echo Windows)

ifeq ($(findstring MINGW,$(UNAME)),MINGW)
    DOCKER_ENV := MSYS_NO_PATHCONV=1
else ifeq ($(findstring MSYS,$(UNAME)),MSYS)
    DOCKER_ENV := MSYS_NO_PATHCONV=1
else
    DOCKER_ENV :=
endif

init:
	$(DOCKER_ENV) docker run --rm \
    -v "$$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    sh -c "composer install --ignore-platform-reqs && chown -R 1000:1000 vendor"
