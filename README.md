# LocMobile Management App

> Complete rental management application for tracking tenants, rents, leases, and payments.

[![Symfony](https://img.shields.io/badge/Symfony-8.x-black?logo=symfony)](https://symfony.com)
[![API Platform](https://img.shields.io/badge/API%20Platform-4.x-blue?logo=api)](https://api-platform.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?logo=php)](https://php.net)
[![Tests](https://img.shields.io/badge/tests-PHPUnit-green?logo=phpunit)](https://phpunit.de)
[![Architecture](https://img.shields.io/badge/architecture-hexagonal-purple)](#)
[![License](https://img.shields.io/badge/license-MIT-yellow)](LICENSE)

---

## Architecture

This project follows **Hexagonal Architecture** (Ports & Adapters), organized by **contexts** (bounded contexts).

```
src/
├── SharedContext/
│   ├── Domain/           # Shared domain interfaces, value objects, exceptions
│   ├── Application/      # Shared ports, DTOs, interfaces
│   ├── Infrastructure/   # Shared adapters, helpers, base repositories
│   └── Presentation/     # Shared controllers, middlewares, API resources
│
└── User/
    ├── Domain/           # User business logic (entities, events, repositories interfaces)
    ├── Application/      # User use cases, commands, queries, handlers
    ├── Infrastructure/   # User repositories (Doctrine), mappers, providers
    └── Presentation/     # User controllers, API Platform resources, serializers
```

Each context (User, Tenant, Lease, Payment, etc.) follows the same structure:

- **Domain** – Core business logic (entities, value objects, domain events, repository interfaces)
- **Application** – Use cases, commands, queries, DTOs, ports
- **Infrastructure** – Concrete implementations (Doctrine repositories, API clients, mappers)
- **Presentation** – Controllers, API resources, request/response transformers

---

## Tests

| Suite             | Status |
| ----------------- | ------ |
| Unit Tests        | ✅     |
| Integration Tests | ✅     |
| Functional Tests  | ✅     |

```bash
# Run all tests
bin/phpunit

# Run specific suite
bin/phpunit tests/Unit
bin/phpunit tests/Integration
```

---

## Quick Start

```bash
# Clone the repository
git clone https://github.com/yourusername/tenant-app.git
cd tenant-app

# Install dependencies
composer install

# Set up database
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate

# Load fixtures (optional)
bin/console doctrine:fixtures:load

# Start the server
symfony serve
```

---

## Features

- Tenant management (CRUD)
- Lease contract tracking
- Rent collection & history
- Payment reminders
- User authentication & roles
- REST API with API Platform
- Automatic API documentation (Swagger / OpenAPI)

---

## Requirements

- PHP 8.4+
- Composer
- Symfony CLI
- MySQL / PostgreSQL
- API Platform

---

## Project Structure

```
.
├── src/
│   ├── SharedContext/
│   │   ├── Domain/
│   │   ├── Application/
│   │   ├── Infrastructure/
│   │   └── Presentation/
│   │
│   └── User/
│       ├── Domain/
│       ├── Application/
│       ├── Infrastructure/
│       └── Presentation/
│
├── tests/
│   ├── Unit/
│   ├── Integration/
│   └── Functional/
├── config/
├── migrations/
└── public/
```

---

## Docker (optional)

```bash
docker-compose up -d
docker exec -it tenant-app bash
composer install
```

---

## License

MIT

---
