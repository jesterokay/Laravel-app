FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libmariadb-dev-compat zip \
    ca-certificates \
    && docker-php-ext-install zip pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Create non-root user
RUN useradd -ms /bin/bash appuser && chown -R appuser:appuser /app

# Copy project files
COPY --chown=appuser:appuser . .

# Switch to non-root user
USER appuser

# Install PHP dependencies without running scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Fix permissions for Laravel directories
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Expose port
EXPOSE 8000

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]