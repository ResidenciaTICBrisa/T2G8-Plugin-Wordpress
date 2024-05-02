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
    
    require 'formulario-admin-page.php';
}