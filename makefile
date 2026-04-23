PHP=php
COMPOSER=composer
APP_ENV=dev

# Fichiers
AUDIT_FILE=/tmp/security-audit.json

# Default
.DEFAULT_GOAL := help

## —— 🎯 Help ——————————————————————————————————————————————
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

## —— 📦 Install ————————————————————————————————————————————
install: ## Install dependencies
	$(COMPOSER) install --prefer-dist --no-interaction --no-progress

install-prod: ## Install without dev deps
	$(COMPOSER) install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

update: ## Update dependencies
	$(COMPOSER) update

## —— 🧹 Quality ————————————————————————————————————————————
lint: ## Run lint (PHP syntax check)
	find . -type f -name "*.php" -print0 | xargs -0 -n1 -P4 $(PHP) -l

cs: ## Fix coding style (PHP-CS-Fixer)
	vendor/bin/php-cs-fixer fix

stan: ## Static analysis (PHPStan)
	vendor/bin/phpstan analyse

## —— 🧪 Tests ——————————————————————————————————————————————
test: ## Run tests (PHPUnit)
	vendor/bin/phpunit

test-coverage: ## Run tests with coverage
	vendor/bin/phpunit --coverage-text

## —— 🔒 Security ——————————————————————————————————————————
audit: ## Run security audit
	$(COMPOSER) audit \
		--no-dev \
		--abandoned=report \
		--format=json \
		> $(AUDIT_FILE)

audit-check: audit ## Fail if vulnerabilities found
	@if [ -s $(AUDIT_FILE) ]; then \
		echo "❌ Vulnerabilities found:"; \
		cat $(AUDIT_FILE); \
		exit 1; \
	else \
		echo "✅ No vulnerabilities"; \
	fi

## —— ⚙️ CI ————————————————————————————————————————————————
ci: install-prod lint stan test audit-check ## Full CI pipeline

## —— 🧼 Cleanup ——————————————————————————————————————————
clean: ## Clean cache & temp files
	rm -rf var/cache/*
	rm -f $(AUDIT_FILE)
