<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Racca</title>
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Nunito', sans-serif;
                background: #f7fafc;
                color: #1a202c;
                height: 100vh;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            .message {
                animation: fadeIn 2s infinite alternate;
            }
        </style>
    </head>
    <body>
        <div>
            <h1>Bem-vindo ao Racca saude!</h1>
            <p class="message">Aguarde... novidades em breve.</p>
        </div>
    </body>
</html>
