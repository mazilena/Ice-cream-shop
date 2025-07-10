FROM php:8.1-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# ✅ Install system dependencies, then install mysqli
RUN apt-get update && \
    apt-get install -y libpng-dev libjpeg-dev libonig-dev libxml2-dev zip unzip && \
    docker-php-ext-install mysqli

# Copy project files
COPY . /var/www/html/

# Set ownership (optional)
RUN chown -R www-data:www-data /var/www/html

# Expose default Apache port
EXPOSE 80
