# Project Overview

This is a Laravel project that uses a modular architecture with the `nwidart/laravel-modules` package. The project includes modules for `Jester` (which appears to be a chat application), `Media` (for handling media files), and `ModuleManagement`. The application uses `spatie/laravel-permission` for role and permission management and `php-ffmpeg/php-ffmpeg` for video processing.

# Building and Running

To run the project in a development environment, use the following command:

```bash
composer run dev
```

This command will start the development server, a queue listener, and Vite for asset compilation.

# Development Conventions

*   The application is modular, so new functionality should be created in a new module.
*   The `Jester` module contains a chat application.
*   The `Media` module is used for managing media files.
*   Roles and permissions are managed using the `spatie/laravel-permission` package.
*   Video processing is handled by the `php-ffmpeg/php-ffmpeg` package.
