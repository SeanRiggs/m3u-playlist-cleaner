FROM php:8.0-cli-alpine

WORKDIR /var/tmp

# Install system dependencies for Composer
RUN apk add --no-cache bash curl

# Install Composer using the official Composer Docker image's recommended approach
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add your composer.json and possibly composer.lock
COPY composer.json composer.lock* ./

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-progress --no-suggest

# Copy the PHP script
COPY playlist_validator.php ./

# Command to run the PHP script
CMD ["php", "/var/tmp/playlist_validator.php"]
