SHELL = /bin/sh

DOCKER ?= $(shell which docker)
DOCKER_REPOSITORY := graze/data-node
VOLUME := /opt/graze/data-node
VOLUME_MAP := -v $$(pwd):${VOLUME}
DOCKER_RUN := ${DOCKER} run --rm -it ${VOLUME_MAP} ${DOCKER_REPOSITORY}:latest

.PHONY: install composer clean help run
.PHONY: test lint lint-fix test-unit test-matrix test-coverage test-coverage-clover

.SILENT: help

install: ## Download the dependencies then build the image :rocket:.
	make 'composer-install --optimize-autoloader --ignore-platform-reqs'
	$(DOCKER) build --tag ${DOCKER_REPOSITORY}:latest .

composer-%: ## Run a composer command, `make "composer-<command> [...]"`.
	${DOCKER} run -t --rm \
        -v $$(pwd):/usr/src/app \
        -v ~/.composer:/root/composer \
        -v ~/.ssh:/root/.ssh:ro \
        graze/composer --ansi --no-interaction $* $(filter-out $@,$(MAKECMDGOALS))

clean: ## Clean up any images.
	$(DOCKER) rmi ${DOCKER_REPOSITORY}:latest

run: ## Run a command on the docker image
	$(DOCKER_RUN) $(filter-out $@,$(MAKECMDGOALS))



test: ## Run the unit and integration testsuites.
test: lint test-unit

lint: ## Run phpcs against the code.
	$(DOCKER_RUN) vendor/bin/phpcs -p --warning-severity=0 src/ tests/

lint-fix: ## Run phpcsf and fix possible lint errors.
	$(DOCKER_RUN) vendor/bin/phpcbf -p src/ tests/

test-unit: ## Run the unit testsuite.
	$(DOCKER_RUN) vendor/bin/phpunit --colors=always --testsuite unit

test-matrix: ## Run the unit tests against multiple targets.
	${DOCKER} run --rm -t ${VOLUME_MAP} -w ${VOLUME} php:5.6-cli \
    vendor/bin/phpunit --testsuite unit
	${DOCKER} run --rm -t ${VOLUME_MAP} -w ${VOLUME} php:7.0-cli \
    vendor/bin/phpunit --testsuite unit
	${DOCKER} run --rm -t ${VOLUME_MAP} -w ${VOLUME} diegomarangoni/hhvm:cli \
    vendor/bin/phpunit --testsuite unit

test-coverage: ## Run all tests and output coverage to the console
	$(DOCKER_RUN) vendor/bin/phpunit --coverage-text

test-coverage-clover: ## Run all tests and output clover coverage to file
	$(DOCKER_RUN) vendor/bin/phpunit --coverage-clover=./tests/report/coverage.clover

test-coverage-html: ## Run all tests and output html coverage to a folder
	$(DOCKER_RUN) vendor/bin/phpunit --coverage-html=./tests/report



help: ## Show this help message.
	echo "usage: make [target] ..."
	echo ""
	echo "targets:"
	egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#' | sort
