
# Demo Clean Architecture

> Example of a Clean Architecture implementation using Symfony and API Platform.

[![Symfony](https://img.shields.io/badge/Symfony-8.x-black?logo=symfony)](https://symfony.com)
[![API Platform](https://img.shields.io/badge/API%20Platform-4.x-blue?logo=api)](https://api-platform.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?logo=php)](https://php.net)
[![PHPUnit](https://img.shields.io/badge/PHPUnit-11.x-green?logo=phpunit)](https://phpunit.de)
[![Architecture](https://img.shields.io/badge/architecture-hexagonal-purple)](#)

---

## Architecture

This project is a demo of a Clean Architecture applied to a modular Symfony application.

It includes an Identity and Access system with:

- Authentication
- Password recovery (forgot password / reset password)
- Email verification
- Phone verification

```
src/
├── SharedContext/
├── IdentityAndAccess/
│   ├── Domain/
│   ├── Application/
│   ├── Infrastructure/
│   └── Presentation/
└── SharedContext/
    ├── Domain
    ├── Application
    └── Infrastructure
```

---

## Testing

| Suite             | Status |
| ----------------- | ------ |
| Unit Tests        | ✅     |
| Integration Tests | ✅     |
| Functional Tests  | ✅     |

```bash
bin/phpunit
```

---

## API

- REST API (API Platform)
- OpenAPI / Swagger
