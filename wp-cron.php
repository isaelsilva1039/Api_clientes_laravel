<?php
/**
 * Arquivo wp-cron.php personalizado para executar o script run_commands.sh.
 */

// Defina o caminho completo para o script run_commands.sh.
$scriptPath = 'run_commands.sh';

// Verifique se o script existe.
if (file_exists($scriptPath)) {
    // Execute o script usando exec() do PHP.
    exec("/bin/bash $scriptPath", $output, $returnValue);

    // Verifique o valor de retorno para determinar se o script foi executado com sucesso.
    if ($returnValue === 0) {
        // O script foi executado com sucesso.
        echo "Script run_commands.sh executado com sucesso.\n";
        echo "Saída: " . implode("\n", $output);
    } else {
        // O script encontrou algum erro.
        echo "Erro ao executar o script run_commands.sh.\n";
        echo "Código de retorno: $returnValue\n";
        echo "Saída de erro: " . implode("\n", $output);
    }
} else {
    // O script não foi encontrado.
    echo "Erro: O arquivo run_commands.sh não foi encontrado no caminho especificado.\n";
}
