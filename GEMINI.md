# Gemini Code Assistant Context

This document provides context for the Gemini Code Assistant to understand the project structure, conventions, and important files.

## Project Overview

This is a Laravel-based web application designed for business management. It appears to be a modular system with features for managing:

*   **Sales & Finances:** Sales, purchases, expenses, and financial summaries.
*   **Inventory:** Products, categories, and inventory summaries.
*   **Human Resources:** Users (employees), departments, positions, roles, and attendance.
*   **CRM:** Customers and suppliers.
*   **System Settings:** Currencies, tax rates, units, payment methods, promotions, discounts, and permissions.

The application uses the `spatie/laravel-permission` package for role-based access control, with roles like "superadmin", "admin", "staff", "intern", and "user". It also includes a modular architecture, with modules located in the `Modules/` directory.

## Key Technologies

*   **Backend:** PHP, Laravel
*   **Frontend:** JavaScript, Vite, Tailwind CSS
*   **Database:** SQLite (as per the `database.sqlite` file)

## Building and Running

1.  **Install Dependencies:**
    *   Run `composer install` to install PHP dependencies.
    *   Run `npm install` to install JavaScript dependencies.

2.  **Set up Environment:**
    *   Copy `.env.example` to `.env` and configure your database and other environment variables.

3.  **Database Migration and Seeding:**
    *   Run `php artisan migrate:fresh --seed` to create the database schema and populate it with initial data.

4.  **Run the Development Server:**
    *   Run `npm run dev` to start the Vite development server for frontend assets.
    *   In a separate terminal, run `php artisan serve` to start the Laravel development server.

## Development Conventions

*   **Modular Architecture:** The application is divided into modules, located in the `Modules/` directory. Each module has its own set of controllers, models, views, and routes.
*   **Routing:**
    *   Web routes are defined in `routes/web.php`.
    *   API routes are defined in `routes/api.php`.
*   **Authentication:** The application uses Laravel's built-in authentication with the `web` guard. The user model is `App\Models\User`.
*   **Permissions:** The `spatie/laravel-permission` package is used for role-based access control. Permissions are defined in the `DatabaseSeeder` and assigned to roles.
*   **Frontend:** The frontend is built using Blade templates, with Tailwind CSS for styling and Vite for asset bundling.
*   **Database:** The project uses Laravel's Eloquent ORM and database migrations.

## Important Files

*   `composer.json`: Defines PHP dependencies, including Laravel and Spatie/laravel-permission.
*   `package.json`: Defines JavaScript dependencies, including Vite and Tailwind CSS.
*   `config/auth.php`: Configures authentication guards and providers.
*   `config/permission.php`: Configures the Spatie/laravel-permission package.
*   `routes/web.php`: Defines web routes.
*   `routes/api.php`: Defines API routes.
*   `database/seeders/DatabaseSeeder.php`: Seeds the database with initial data, including roles and permissions.
*   `app/Models/User.php`: The user model, which uses the `HasRoles` trait.
*   `resources/views/layouts/partials/sidebar.blade.php`: The main navigation sidebar for the application.
*   `vite.config.js`: Configuration for the Vite asset bundler.
