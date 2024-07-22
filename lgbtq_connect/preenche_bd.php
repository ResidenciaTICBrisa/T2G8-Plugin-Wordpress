<?php
# Primeiro verifique se a tabela (lc_formulario) está vazia
# Para executar o preenchimento do BD
# Acesse http://seusite.com/wp-content/plugins/lgbtq_connect/preenche_bd.php

// Incluir o WordPress
define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');

// Configuração do banco de dados
global $wpdb;
$table_name = 'lc_formulario';

// Número de linhas a serem inseridas
$num_rows = 2000;

// Arrays com valores possíveis para situacao e servico
$situacao_opcoes = array('Aprovado', 'Negado', 'Pendente');
$servico_opcoes = array('bar/restaurante', 'entretenimento', 'beleza', 'hospedagem', 'ensino', 'academia');

// Loop para inserir dados
for ($i = 1; $i <= $num_rows; $i++) {
    $data = array(
        'id' => $i,
        'nome' => 'Nome ' . $i,
        'email' => 'email' . $i . '@example.com',
        'latitude' => rand(-90, 90) + (rand(0, 9999) / 10000),
        'longitude' => rand(-180, 180) + (rand(0, 9999) / 10000),
        'road' => 'Rua ' . $i,
        'city' => 'Cidade ' . $i,
        'data_hora' => date('Y-m-d H:i:s'),
        'servico' => $servico_opcoes[array_rand($servico_opcoes)],
        'descricao' => 'Descrição ' . $i,
        'situacao' => $situacao_opcoes[array_rand($situacao_opcoes)]
    );

    // Inserir dados na tabela
    $wpdb->insert($table_name, $data);
}

echo "Dados inseridos com sucesso!";
?>
