<?php

// Instale as dependências do composer
exec("composer install");

// Gere uma chave de criptografia
exec("php artisan key:generate");

// Rode as migrações do banco de dados
exec("php artisan migrate");

// Adicione a tarefa agendada ao cron
?>