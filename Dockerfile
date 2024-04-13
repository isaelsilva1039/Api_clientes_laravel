# Use a imagem oficial do PHP com Apache
FROM php:7.4-apache

# Copiar os arquivos do projeto para o diretório padrão do Apache
COPY . /var/www/html

# Instalar extensões do PHP necessárias para Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Dar permissão ao diretório de armazenamento do Laravel
RUN chown -R www-data:www-data /var/www/html/storage
RUN chmod -R 775 /var/www/html/storage
