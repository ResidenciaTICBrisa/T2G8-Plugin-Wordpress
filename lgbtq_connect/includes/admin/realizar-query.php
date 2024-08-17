<?php

require_once plugin_dir_path(__FILE__) . '../data/conexao_bd.php';

function realizarQuery() {
    global $wpdb;

    // Verifica se os dados existem
    if (!isset($_POST['formData'])) {
        wp_send_json_error(['message' => 'Pedido inválido']);
        return;
    }

    $dados = $_POST['formData'];

    // Prepara o SQL
    $sql = "SELECT * FROM lc_formulario WHERE 1=1";
    $sql_contagem = "SELECT COUNT(*) FROM lc_formulario WHERE 1=1";

    // Prepara os parâmetros do query
    $params = [];
    
    // Filtra de acordo com o nome
    $nome = '%' . str_replace(' ', '', $dados['nome']) . '%';
    $sql .= " AND REPLACE(LOWER(nome), ' ', '') LIKE LOWER(%s)";
    $sql_contagem .= " AND REPLACE(LOWER(nome), ' ', '') LIKE LOWER(%s)";    
    $params[] = $nome;
    
    // Filtra de acordo com o status
    $status = '%' . str_replace(' ', '', $dados['status']) . '%';
    $sql .= " AND REPLACE(LOWER(situacao), ' ', '') LIKE LOWER(%s)";
    $sql_contagem .= " AND REPLACE(LOWER(situacao), ' ', '') LIKE LOWER(%s)";
    $params[] = $status;

    // Filtra de acordo com o serviço
    $servico = '%' . str_replace(' ', '', $dados['servico']) . '%';
    $sql .= " AND REPLACE(LOWER(servico), ' ', '') LIKE LOWER(%s)";
    $sql_contagem .= " AND REPLACE(LOWER(servico), ' ', '') LIKE LOWER(%s)";
    $params[] = $servico;

    // Ordenação
    $ordem = strtoupper($dados['ordem']) === 'ASC' ? 'DESC' : 'ASC';
    $sql .= " ORDER BY {$dados['coluna']} $ordem";

    // Paginação
    $items_por_pagina = 10;
    $pagina_atual = !empty($dados['pagina']) ? (int)$dados['pagina'] : 1;
    $offset = ($pagina_atual - 1) * $items_por_pagina;
    $sql .= $wpdb->prepare(" LIMIT %d OFFSET %d", $items_por_pagina, $offset);

    // Prepare e realiza o query de contagem
    $query_contagem_preparado = $wpdb->prepare($sql_contagem, $params);
    $total_items = $wpdb->get_var($query_contagem_preparado);

    // Prepara e realiza o query principal
    $query_preparado = $wpdb->prepare($sql, $params);
    $resultados = $wpdb->get_results($query_preparado, ARRAY_A);

    // Manda os resultados de volta para o JavaScript
    wp_send_json_success([
        'resultados' => $resultados,
        'total_items' => $total_items
    ]);
}
?>
