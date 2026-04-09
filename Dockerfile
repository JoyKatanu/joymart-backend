# Use official PHP with Apache
FROM php:8.2-apache

# Install PostgreSQL dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pgsql pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite (good practice)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY joymart_api/ /var/www/html/

# Create uploads folder (VERY IMPORTANT)
RUN mkdir -p /var/www/html/uploads \
    && chmod -R 777 /var/www/html/uploads

# Set proper ownership for Apache
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]