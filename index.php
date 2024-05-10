<?php

/**
 * Redireciona todas as solicitações para o diretório 'public'
 */

$public = 'public/';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = '/'. ltrim($uri, '/');

$target = $public . $uri;
if(file_exists(__DIR__ . $target)) {
    // Se o arquivo existir em 'public', sirva esse arquivo diretamente
    return require_once __DIR__ . $target;
}

// Se não, redirecione para 'public/index.php'
require_once __DIR__ . '/public/index.php';
