<?php
/*
Plugin Name: LGBTQ+ Connect
Plugin URI: https://residenciaticbrisa.github.io/T2G8-Plugin-Wordpress/
Description: Mapa LGBTQ+ com cadastro e validação admin, promovendo locais acolhedores para a comunidade.
Version: 0.3.0
Author: Igor Brandão, Gustavo Linhares, Marcos Vinicius, Max Rohrer e Will Bernardo
*/

//Display de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui os arquivos necessários
include_once(plugin_dir_path(__FILE__) . 'includes/data/conexao_bd.php');
include_once(plugin_dir_path(__FILE__) . 'includes/data/process_form.php');
include_once(plugin_dir_path(__FILE__) . 'includes/admin/pagina_administracao.php');

// Adiciona um gancho para processar o formulário quando o WordPress estiver processando solicitações
add_action('wp_ajax_processar_formulario', 'processar_formulario');
add_action('wp_ajax_nopriv_processar_formulario', 'processar_formulario');

// Função para carregar o conteúdo do arquivo HTML
function load_meu_plugin_html() {
    // Obtém o caminho do arquivo HTML dentro do diretório do plugin
    $html_file = plugin_dir_path(__FILE__) . 'assets/index.html';

    // Retorna o conteúdo do arquivo HTML
    return file_get_contents($html_file);
}

// Função para enfileirar o script.js
function load_meu_plugin_scripts() {
    wp_enqueue_script('meu-plugin-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array(), '1.0', true);
}

// Função para enfileirar o estilo CSS
function load_meu_plugin_styles() {
    wp_enqueue_style('meu-plugin-style', plugin_dir_url(__FILE__) . 'assets/styles/styles.css', array(), '1.0');
    // Localiza o URL do AJAX no arquivo admin-ajax.php
    wp_localize_script( 'meu-plugin-script', 'my_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

// Adiciona um gancho para enfileirar os scripts
add_action('wp_enqueue_scripts', 'load_meu_plugin_scripts');

// Adiciona um gancho para enfileirar os estilos
add_action('wp_enqueue_scripts', 'load_meu_plugin_styles');

// Função para adicionar o shortcode
function meu_plugin_shortcode() {
    // Obtém o conteúdo do arquivo HTML
    $html_content = load_meu_plugin_html();
    
    // Retorna o conteúdo do arquivo HTML
    return $html_content;
}

// Registra o shortcode com o nome 'meu_plugin'
add_shortcode('lgbtq_connect', 'meu_plugin_shortcode');

// Adiciona o nome do arquivo ao plugin
define('MEU_PLUGIN_FILE', __FILE__);
