# Use a imagem base do PHP
FROM php:8.1-fpm

# Instale as extensões do PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Copie os arquivos do seu projeto para o contêiner
COPY . /var/www/html

# Instale o servidor web Nginx e configure-o
RUN apt-get update && apt-get install -y nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Exponha a porta 80 para acesso externo
EXPOSE 80

# Inicialize o servidor web e o PHP
CMD ["nginx", "-g", "daemon off;"]
