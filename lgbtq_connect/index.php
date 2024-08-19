<?php
/*
Plugin Name: LGBTQ+ Connect
Plugin URI: https://residenciaticbrisa.github.io/T2G8-Plugin-Wordpress/
Description: Adição de mapa com cadastro e validação, promovendo locais acolhedores para a comunidade LGBTQ+.
Version: 0.34.0
Author: Igor Brandão, Gustavo Linhares, Marcos Vinicius, Max Rohrer e Will Bernardo
License: GPL v2 or later
*/

//Display de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui os arquivos necessários
include_once(plugin_dir_path(__FILE__) . 'includes/data/conexao_bd.php');
include_once(plugin_dir_path(__FILE__) . 'includes/data/process_form.php');
include_once(plugin_dir_path(__FILE__) . 'includes/admin/realizar-query.php');
include_once(plugin_dir_path(__FILE__) . 'includes/admin/pagina_administracao.php');

// Adiciona um gancho para processar o formulário quando o WordPress estiver processando solicitações
add_action('wp_ajax_processar_formulario', 'processar_formulario');
add_action('wp_ajax_nopriv_processar_formulario', 'processar_formulario');

add_action('wp_ajax_realizarQuery', 'realizarQuery');
add_action('wp_ajax_nopriv_realizarQuery', 'realizarQuery');

// Função para carregar o conteúdo do arquivo HTML
function load_meu_plugin_html() {
    // Obtém o caminho do arquivo HTML dentro do diretório do plugin
    $html_file = plugin_dir_path(__FILE__) . 'assets/index.html';

    // Retorna o conteúdo do arquivo HTML
    return file_get_contents($html_file);
}

// Função para enfileirar o script.js
function load_meu_plugin_scripts() {
    wp_enqueue_script('formulario_script', plugin_dir_url(__FILE__) . 'assets/js/formulario.js', array(), '1.0', true);
    wp_enqueue_script('funcionalidades_script', plugin_dir_url(__FILE__) . 'assets/js/funcionalidades.js', array(), '1.0', true);
}

// Função para enfileirar o estilo CSS
function load_meu_plugin_styles() {
    wp_enqueue_style('meu-plugin-style', plugin_dir_url(__FILE__) . 'assets/styles/styles.css', array(), '1.0');
    // Localiza o URL do AJAX no arquivo admin-ajax.php
    wp_localize_script( 'formulario_script', 'my_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

function enfileirar_scripts_admin() {
    global $wpdb;
    // Obtém os formulários aprovados
    $formularios_aprovados = obter_formularios_aprovados($wpdb);

    // Obtém todos os formulários
    $formularios = obter_formularios($wpdb);

    // Url dos Marcadores
    $marcadores = array(
        'academia' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Academia.png',
        'bar/restaurante' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Bares_Restaurante.png',
        'beleza' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Beleza.png',
        'ensino' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Ensino.png',
        'entretenimento' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Entretenimento.png',
        'hospedagem' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Hospedagem.png',
        'outro' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Padrao.png',
    );

    wp_enqueue_script('admin_script.js', plugin_dir_url(__FILE__) . 'includes/admin/admin_script.js', array('jquery'), '1.0', true);
    
    wp_localize_script( 'admin_script.js', 'my_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

    // Passa os dados dos formulários aprovados para o script JavaScript
    wp_localize_script('admin_script.js', 'formularios_aprovados', $formularios_aprovados);

    // Passa os dados de todos os formulários para o script JavaScript
    wp_localize_script('admin_script.js', 'formularios_todos', $formularios);

    // Passa o url do marcador
    wp_localize_script('admin_script.js', 'marcadores', $marcadores);
}

function enfileirar_styles_admin()
{
    wp_enqueue_style('admin-style', plugin_dir_url(__FILE__) . 'includes/admin/style-admin.css', array(), '1.0');
}

// Função para criar a tabela na ativação do plugin
function add_tabela_bd() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    criar_tabela_formulario($wpdb);
}

register_activation_hook(__FILE__, 'add_tabela_bd');

// Enfileira o script JavaScript e passa os dados dos formulários aprovados para ele
function enfileirar_scripts() {
    global $wpdb;

    // Enfileira o script JavaScript
    wp_enqueue_script('script-js', plugins_url('/assets/js/script.js', __FILE__), array('jquery'), '1.0', true);

    // Obtém os formulários aprovados
    $formularios_aprovados = obter_formularios_aprovados($wpdb);
    
    // Url para o marcador que irá aparecer quando ele for clicado no mapa de cadastro
    $marcadormapa = plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Padrao.png';

    // Url dos Marcadores
    $marcadores = array(
        'academia' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Academia.png',
        'bar/restaurante' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Bares_Restaurante.png',
        'beleza' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Beleza.png',
        'ensino' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Ensino.png',
        'entretenimento' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Entretenimento.png',
        'hospedagem' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Hospedagem.png',
        'outro' => plugin_dir_url(__FILE__) . 'assets/imgs/Marker_Padrao.png',
    );
    
    // Obtém todos os formulários
    $formularios = obter_formularios($wpdb);

    // Passa os dados para o script JavaScript
    wp_localize_script('script-js', 'formularios_aprovados', $formularios_aprovados);
    wp_localize_script('script-js', 'formularios_todos', $formularios);
    wp_localize_script('script-js', 'marcadores', $marcadores);
    wp_localize_script('script-js', 'marcador_mapa', $marcadormapa);
}

add_action('admin_enqueue_scripts', 'enfileirar_scripts_admin');
add_action('admin_enqueue_scripts', 'enfileirar_styles_admin');

// Função para adicionar o shortcode
function lc_shortcode() {
    // Obtém o conteúdo do arquivo HTML
    $html_content = load_meu_plugin_html();
    enfileirar_scripts();
    load_meu_plugin_scripts();
    load_meu_plugin_styles();
    
    // Retorna o conteúdo do arquivo HTML
    return $html_content;
}

// Registra o shortcode com o nome 'lgbtq_connect'
add_shortcode('lgbtq_connect', 'lc_shortcode');

function lc_register_block() {
    // Registra o script do bloco
    wp_register_script(
        'lgbtq-connect-block',
        plugins_url('/assets/js/block.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-editor'),
        filemtime(plugin_dir_path(__FILE__) . '/assets/js/block.js')
    );


    wp_register_style(
        'lgbtq-connect-block-editor',
        plugins_url('/assets/styles/block_style.css', __FILE__),
        array('wp-edit-blocks'),
        filemtime(plugin_dir_path(__FILE__) . '/assets/styles/block_style.css')
    );

    // Registra o bloco
    register_block_type('lgbtq-connect/custom-block', array(
        'editor_script' => 'lgbtq-connect-block',
        'editor_style'  => 'lgbtq-connect-block-editor',
        'render_callback' => 'lc_shortcode',
    ));
}
add_action('init', 'lc_register_block');