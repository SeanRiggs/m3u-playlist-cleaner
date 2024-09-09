FROM php:8.0-cli-alpine

WORKDIR /var/tmp

# Install system dependencies for Composer
RUN apk add --no-cache bash curl

# Install Composer using the official Composer Docker image's recommended approach
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-progress

# Copy the PHP script and create necessary directories
COPY playlist_validator.php ./
RUN mkdir -p /var/tmp/m3u

# Set up environment variables
ENV LOG_FILE=/var/tmp/m3u/validator.log
ENV OUTPUT_FILE=/var/tmp/m3u/playlist.m3u
ENV M3U_DIRECTORY=/var/tmp/m3u

# Command to run the PHP script
CMD ["php", "/var/tmp/playlist_validator.php"]
