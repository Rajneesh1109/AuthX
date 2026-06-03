FROM php:8.2-apache

# Install PostgreSQL libraries and PHP extensions
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Enable Apache mod_rewrite for URL routing if needed
RUN a2enmod rewrite

# Copy all project files into the container's web root
COPY . /var/www/html/

# Make sure the uploads directory is writable
RUN mkdir -p /var/www/html/uploads && chmod 777 /var/www/html/uploads

# Expose port 80
EXPOSE 80
