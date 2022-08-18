# This will output the help for each task. thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
help: ## Show this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)


all: help

build: ## Build containers
	@docker-compose build

up: build ## Start docker composer
	@docker-compose up -d --force-recreate

down: ## Stop docker containers
	@docker-compose down

tests-run: ## Run all tests
	@docker-compose exec -- app php vendor/bin/codecept run -v

tests-prepare: ## build codeception
	@docker-compose exec -- app php vendor/bin/codecept build

runin: ## Run command in app container
	@docker-compose exec -- app $(filter-out $@,$(MAKECMDGOALS))

%:
	@:
