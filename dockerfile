# Use an official PHP Apache image
FROM php:8.3-apache

# Install system dependencies and the necessary PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    default-mysql-client \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the 'src' directory from your local machine to the container's /var/www/html
COPY src/ /var/www/html/

# Configure Apache to serve the 'src' folder as the document root
RUN echo 'DocumentRoot /var/www/html' > /etc/apache2/sites-available/000-default.conf \
    && echo 'DirectoryIndex index.php index.html' >> /etc/apache2/apache2.conf

# Add a ServerName to suppress Apache's warning
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Set proper permissions for Apache to access the files
RUN chown -R www-data:www-data /var/www/html

# Expose the port Apache is running on (default 80)
EXPOSE 80

# Run Apache in the foreground (to keep the container running)
CMD ["apache2-foreground"]
