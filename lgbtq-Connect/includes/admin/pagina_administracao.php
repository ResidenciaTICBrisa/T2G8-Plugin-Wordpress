<?php
// Adiciona uma página ao menu do painel de administração
function adicionar_pagina_administracao() {
    add_menu_page(
        'LGBTQ+ Connect', // Título da página de administração
        'LGBTQ+ Connect', // Título do menu do painel de administração
        'manage_options',
        'lc_admin',
        'mostrar_dados',
        'dashicons-admin-users',
        6
    );
}

add_action('admin_menu', 'adicionar_pagina_administracao');

// Função para mostrar os dados na página do painel de administração
function mostrar_dados() {
    global $wpdb;
    // Consulta os dados da tabela formulario
    $dados_formulario = $wpdb->get_results("SELECT * FROM lc_formulario");
    
    // Verifica se uma ação de exclusão foi acionada
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        // Captura o ID do registro a ser excluído
        $id = intval($_GET['id']);

        // Executa a consulta para excluir o registro
        $resultado_exclusao = $wpdb->delete('lc_formulario', array('id' => $id));

        // Exibe uma mensagem de sucesso ou erro
        if ($resultado_exclusao === false) {
            echo '<div class="error"><p>Erro ao excluir o registro!</p></div>';
        } else {
            echo '<div class="updated"><p>Registro excluído com sucesso!</p></div>';
        }
    }

    require 'formulario-admin-page.php';

}


