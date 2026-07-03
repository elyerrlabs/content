# Elymod App

**Elymod App** is the official module skeleton used by OAuth2 Passport Server to generate Elymod modules.

It provides a preconfigured application structure, development environment, and tooling required to build isolated modules that seamlessly integrate with the OAuth2 Passport Server ecosystem.

Designed for a plug-and-play experience, Elymod App allows developers to focus on business logic while the platform handles authentication, authorization, dependency isolation, and core services.

---

# Purpose

Elymod App serves as the blueprint for all Elymod modules.

When a new module is created, OAuth2 Passport Server generates a fresh module instance based on this skeleton, ensuring consistency across the ecosystem.

---

# Creating Modules

Modules are generated directly from OAuth2 Passport Server:

```bash
php artisan module:make ModuleName
```

This command creates a fully configured Elymod module ready for development.

---

# Features

- Official Elymod module skeleton
- Plug-and-play integration with OAuth2 Passport Server
- Preconfigured development environment
- Qasi-powered frontend tooling
- Laravel-inspired project structure
- Isolated module architecture
- Independent routing, views, middleware, and configuration
- Elyscope dependency isolation support
- Enterprise-ready modular foundation

---

# Dependency Isolation

Elymod modules are designed to work alongside Elyscope, the dependency isolation layer of the Elymod ecosystem.

Elyscope extends Composer by applying module-specific dependency scoping and namespace isolation, allowing multiple modules to use different versions of the same packages without causing conflicts.

This approach helps ensure that modules remain self-contained and independent from both the host application and other installed modules.

---

# Architecture

Each generated module owns its:

- Routes
- Controllers
- Views
- Middleware
- Policies
- Configuration
- Assets
- Dependencies
- Business logic

while remaining isolated from the host application.

This ensures that modules can evolve independently without affecting platform stability.

---

# Development Philosophy

> The platform owns authentication and authorization.
>
> Modules own business functionality.

OAuth2 Passport Server provides the core platform services, while Elymod modules extend the platform through independent and portable functionality.

---

# Ecosystem

- OAuth2 Passport Server
- Elymod
- Elymod App
- Elyscope
- Laravel Runtime
- Third-party Elymod Modules

---

# License

MIT License.
