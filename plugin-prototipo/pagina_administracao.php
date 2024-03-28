<?php
// Adiciona a conexão com banco de dados
include_once('conexao_bd.php');

// Adiciona uma página ao menu do painel de administração
function adicionar_pagina_administracao() {
    add_menu_page(
        'Formulários', // Título da página de administração
        'Formulários', // Título do menu do painel de administração
        'manage_options',
        'meus_dados',
        'mostrar_dados',
        'dashicons-admin-users',
        6
    );
}
add_action('admin_menu', 'adicionar_pagina_administracao');

// Função para mostrar os dados na página do painel de administração
function mostrar_dados() {
    global $wpdb;
    
    // Verifica se uma ação de exclusão foi acionada
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        // Captura o ID do registro a ser excluído
        $id = intval($_GET['id']);

        // Executa a consulta para excluir o registro
        $wpdb->delete($wpdb->formulario . 'formulario', array('id' => $id));

        // Exibe uma mensagem de sucesso
        echo '<div class="updated"><p>Registro excluído com sucesso!</p></div>';
    }

    // Consulta os dados da tabela formulario
    $dados_formulario = $wpdb->get_results("SELECT * FROM {$wpdb->formulario}formulario");

    // Verifica se a consulta retornou resultados
    if ($dados_formulario) {
        // Exibe os dados na página
        echo '<div class="wrap">';
        echo '<h2>Dados do Formulário</h2>';
        echo '<table class="wp-list-table widefat striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Nome</th>';
        echo '<th>Email</th>';
        echo '<th>Latitude</th>';
        echo '<th>Longitude</th>';
        echo '<th>Descrição</th>';
        echo '<th>Ações</th>'; // Coluna de ações
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($dados_formulario as $dados) {
            echo '<tr>';
            echo '<td>' . $dados->nome . '</td>';
            echo '<td>' . $dados->email . '</td>';
            echo '<td>' . $dados->latitude . '</td>';
            echo '<td>' . $dados->longitude . '</td>';
            echo '<td>' . $dados->descricao . '</td>';
            echo '<td><a href="?page=meus_dados&action=delete&id=' . $dados->id . '">Excluir</a></td>'; // Link de exclusão
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        // Se não houver resultados, exibe uma mensagem de erro
        echo '<div class="wrap">';
        echo '<h2>Dados do Formulário</h2>';
        echo '<p>Não foi possível recuperar os dados do formulário ou o banco de dados está vazio.</p>';
        echo '</div>';
    }
}?>