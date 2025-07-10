FROM php:8.1-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# ✅ Install required PHP extensions
RUN apt-get update && \
    apt-get install -y libpng-dev libjpeg-dev libonig-dev libxml2-dev zip unzip && \
    docker-php-ext-install mysqli pdo pdo_mysql

# ✅ Set home.php as default page
RUN echo "DirectoryIndex home.php" >> /etc/apache2/apache2.conf

# ✅ Copy project files to Apache root
COPY . /var/www/html/

# Optional: Fix permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
