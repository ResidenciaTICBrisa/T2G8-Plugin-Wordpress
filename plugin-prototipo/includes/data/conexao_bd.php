<?php
/**
 * Função para obter informações de conexão com o banco de dados do WordPress.
 * Retorna um array associativo com as informações de conexão.
 */

 function obter_informacoes_bd_wordpress() {
    $wp_config_path = ABSPATH . 'wp-config.php';
    if (file_exists($wp_config_path)) {
        require_once($wp_config_path);
        if (defined('DB_NAME') && defined('DB_USER') && defined('DB_PASSWORD') && defined('DB_HOST')) {
            return array(
                'nome_bd' => DB_NAME,
                'usuario' => DB_USER,
                'senha' => DB_PASSWORD,
                'host' => DB_HOST,
            );
        }
    }
    return null;
}

$informacoes_bd = obter_informacoes_bd_wordpress();

if ($informacoes_bd) {
    $host = $informacoes_bd['host'];
    $usuario = $informacoes_bd['usuario'];
    $senha = $informacoes_bd['senha'];
    $banco_dados = $informacoes_bd['nome_bd'];

    global $wpdb;
    $table_name = $wpdb->prefix . 'formulario';
    $charset_collate = $wpdb->get_charset_collate();

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            latitude FLOAT(10, 6) NOT NULL,
            longitude FLOAT(10, 6) NOT NULL,
            descricao TEXT NOT NULL
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    register_activation_hook(__FILE__, 'criar_tabela_formulario');
}

function criar_tabela_formulario() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'formulario';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        latitude FLOAT(10, 6) NOT NULL,
        longitude FLOAT(10, 6) NOT NULL,
        descricao TEXT NOT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
