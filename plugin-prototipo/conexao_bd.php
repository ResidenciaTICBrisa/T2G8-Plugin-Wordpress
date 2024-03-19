<?php
/**
 * Função para obter informações de conexão com o banco de dados do WordPress.
 * Retorna um array associativo com as informações de conexão.
 */

function obter_informacoes_bd_wordpress() {
    // Verifica se o arquivo wp-config.php existe
    $wp_config_path = ABSPATH . 'wp-config.php';
    if (file_exists($wp_config_path)) {
        // Inclui o arquivo wp-config.php
        require_once($wp_config_path);

        // Verifica se as constantes de conexão com o banco de dados estão definidas
        if (defined('DB_NAME') && defined('DB_USER') && defined('DB_PASSWORD') && defined('DB_HOST')) {
            // Retorna as informações de conexão
            return array(
                'nome_bd' => DB_NAME,
                'usuario' => DB_USER,
                'senha' => DB_PASSWORD,
                'host' => DB_HOST,
            );
        }
    }

    // Retorna null se não puder obter as informações de conexão
    return null;
}

$informacoes_bd = obter_informacoes_bd_wordpress();

// Configurações do banco de dados
$host = $informacoes_bd['host']; // Host do banco de dados (no caso do docker é db o nome do serviço)
$usuario = $informacoes_bd['usuario']; // Nome de usuário do banco de dados
$senha = $informacoes_bd['senha']; // Senha do banco de dados
$banco_dados = $informacoes_bd['nome_bd']; // Nome do banco de dados

// Estabelece a conexão com o banco de dados
$conexao = mysqli_connect($host, $usuario, $senha, $banco_dados);

// Verifica a conexão
if (!$conexao) {
    die('Erro de conexão com o banco de dados: ' . mysqli_connect_error());
}

// Verifica se a tabela existe, se não existir, cria a tabela
$table_name = 'formulario';
$query = "SHOW TABLES LIKE '{$table_name}'";
$result = mysqli_query($conexao, $query);

if (mysqli_num_rows($result) == 0) {
    $create_table_query = "CREATE TABLE {$table_name} (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        latitude FLOAT(10, 6) NOT NULL,
        longitude FLOAT(10, 6) NOT NULL,
        descricao TEXT NOT NULL
    )";

    if (!mysqli_query($conexao, $create_table_query)) {
        die('Erro ao criar a tabela: ' . mysqli_error($conexao));
    }
}
?>