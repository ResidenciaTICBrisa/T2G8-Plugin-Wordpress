<?php
/*
Plugin Name: LGBTQ+ Connect
Description: Adiciona um formulário simples a uma página WordPress com um mapa Leaflet.
Version: 0.3.1
Author: Will Bernardo, Igor Brandão, Max Rohrer e Gustavo Linhares
*/


include('formulario.php');
include('mapa_config.JS');
include('process_forms.php');

// Adiciona o shortcode [mostrar_mapa] para mostrar o mapa na página
add_shortcode('mostrar_mapa', 'mostrar_mapa');

// Adiciona um gancho para processar o formulário quando o WordPress estiver processando solicitações
add_action('init', 'processar_formulario');

// Função para carregar os estilos CSS
function carregar_estilos_index() {
    // Registra o arquivo CSS
    wp_register_style('index_css', plugins_url('./styles/style_index.css', __FILE__));
    // Enfila o arquivo CSS registrado
    wp_enqueue_style('index_css');

    // Registra o arquivo CSS
    wp_register_style('meu_formulario_css', plugins_url('./styles/style_form.css', __FILE__));
    // Enfila o arquivo CSS registrado
    wp_enqueue_style('meu_formulario_css');
}

// Adiciona um gancho para carregar os estilos
add_action('wp_enqueue_scripts', 'carregar_estilos_index');


function mostrar_mapa(){
    //Buffer para armazenar dados temporários
    ob_start(); ?>
    <head>
        <!-- Carregar ícones padrão do Leaflet do CDN -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.css" />
    </head>
    <div id="div-index" >
        <!-- Container para o mapa -->
        <div id="mapa" style="height: 500px;"></div>
        <a href="<?php echo get_permalink(add_pagina()); ?>" id="botao-redirecionar">Cadastrar um novo local</a>
    </div>

    <!-- Inclui a biblioteca Leaflet -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <?php
    // Saída do buffer
    return ob_get_clean();
}

// Função para mostrar o formulário
function mostrar_formulario() {
    //Buffer para armazenar dados temporários
    ob_start(); ?>
    <head>
        <!-- Carregar ícones padrão do Leaflet do CDN -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.css" />
    </head>
    <form id="meu_formulario" method="post">
        <label for="nome" id="labelnome">Nome do local:</label>
        <input type="text" name="nome" id="nome" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        
        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" rows="3" cols="70" placeholder="Descrição ..." required></textarea>

        <!-- Container para o mapa -->
        <div id="mapa" style="height: 300px;"></div>
        
        <!-- Input de latitude e longitude --> 
        <input type="hidden" name="latitude" id="latitude" required>
        <input type="hidden" name="longitude" id="longitude" required>

        <input type="submit" value="Enviar">
    </form>

    <!-- Inclui a biblioteca Leaflet -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <?php
    // Saída do buffer
    return ob_get_clean();
}

