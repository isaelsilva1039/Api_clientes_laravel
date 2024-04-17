# Usar a imagem oficial do PHP com Apache que suporta PHP 8.1
FROM php:8.1-apache

# Instale as extensões necessárias para o Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Configurar o nome do servidor para evitar avisos do Apache
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf
# Ative o mod_rewrite para o Apache
RUN a2enmod rewrite

# Definir o diretório de trabalho para /var/www/html
WORKDIR /var/www/html

# Copie seus arquivos de código fonte para o diretório padrão do Apache
COPY . /var/www/html

# Ajuste as permissões do diretório
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Instale as dependências do Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Exponha a porta 80
EXPOSE 80


# Comando para executar o servidor Apache em primeiro plano
CMD ["apache2-foreground"]
