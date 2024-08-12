<?php

// Verifique se o WordPress está chamando o arquivo diretamente
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Deletar a tabela do banco de dados
global $wpdb;
$table_name = 'lc_formulario';
$wpdb->query("DROP TABLE IF EXISTS $table_name");

// Deletar arquivos e diretórios do plugin
function delete_plugin_files($dir) {
    // Verifica se o diretório existe
    if (!file_exists($dir)) {
        return false;
    }
    // Abre o diretório
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        // Se for um diretório, faz uma chamada recursiva
        if (is_dir("$dir/$file")) {
            delete_plugin_files("$dir/$file");
        } else {
            // Deleta o arquivo
            unlink("$dir/$file");
        }
    }
    // Deleta o diretório
    return rmdir($dir);
}

// Caminho completo para a pasta do plugin
$plugin_dir = plugin_dir_path(__FILE__);
delete_plugin_files($plugin_dir);

