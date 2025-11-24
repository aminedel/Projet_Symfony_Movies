FROM php:8.2-apache

# Installer PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite d'Apache pour Symfony
RUN a2enmod rewrite

WORKDIR /var/www/html
