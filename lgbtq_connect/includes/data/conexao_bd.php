<?php
/**
 * Nesse arquivo são armazenadas as funcionalidades de conexão com o banco de dados
 * configurados no arquivo wp-config.php do WordPress.
 */

// Obtém as informações do banco de dados
function obter_informacoes_bd($config_path) {
    if (file_exists($config_path)) {
        require_once($config_path);
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

// Criação da tabela para armazenamento dos formulários cadastrados
function criar_tabela_formulario($wpdb) {
    $table_name = 'lc_formulario';
    $charset_collate = $wpdb->get_charset_collate();
    
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            latitude FLOAT(10, 6) NOT NULL,
            longitude FLOAT(10, 6) NOT NULL,
            rua VARCHAR(255),
            cidade VARCHAR(255),
            data_hora VARCHAR(100) NOT NULL,
            servico VARCHAR(30) NOT NULL,
            descricao TEXT NOT NULL,
            situacao VARCHAR(20) NOT NULL DEFAULT 'Pendente'
        ) $charset_collate;";
        dbDelta($sql);
    }
}

// Função para retornar os formulários aprovados (Return é um array)
function obter_formularios_aprovados($wpdb) {

    // Monta a consulta SQL com base nos parâmetros de ordenação
    $query = "SELECT * FROM lc_formulario";
    $dados_formulario = $wpdb->get_results($query);

    // Array para armazenar os formulários aprovados
    $formularios_aprovados = array();

    // Itera sobre cada formulário
    foreach ($dados_formulario as $formulario) {
        // Verifica se o formulário está aprovado
        if ($formulario->situacao === 'Aprovado') {
            // Adiciona o formulário ao array de formulários aprovados
            $formularios_aprovados[] = $formulario;
        }
    }
    
    return $formularios_aprovados;
}

// Função para retornar os formulários (Return é um array)
function obter_formularios($wpdb) {

    // Monta a consulta SQL com base nos parâmetros de ordenação
    $query = "SELECT * FROM lc_formulario";
    $dados_formulario = $wpdb->get_results($query);

    // Arraylongitude para armazenar os formulários aprovados
    $formularios = array();

    // Itera sobre cada formulário
    foreach ($dados_formulario as $formulario) {
        // Adiciona cada formulário ao array de formulários
        $formularios[] = $formulario;
    }
    
    return $formularios;
}
?>
