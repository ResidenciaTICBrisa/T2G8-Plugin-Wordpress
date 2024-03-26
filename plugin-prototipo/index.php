<?php
/*
Plugin Name: LGBTQ+ Connect
Description: Mapa LGBTQ+ com cadastro e validação admin, promovendo locais acolhedores para a comunidade.
Version: 0.4.0
Author: Will Bernardo, Igor Brandão, Max Rohrer e Gustavo Linhares
*/

include('formulario.php');
include('process_forms.php');
include('pagina_administracao.php');

// Adiciona o shortcode [mostrar_mapa] para mostrar o mapa na página
add_shortcode('mostrar_mapa', 'mostrar_mapa');

// Adiciona um gancho para processar o formulário quando o WordPress estiver processando solicitações

add_action('init', 'processar_formulario');


// Função para carregar os estilos CSS
function carregar_arquivos() {
    
    // Registra o arquivo CSS
    wp_register_style('index_css', plugins_url('./styles/style_index.css', __FILE__));
    // Enfila o arquivo CSS registrado
    wp_enqueue_style('index_css');
}

// Adiciona um gancho para carregar os estilos
add_action('wp_enqueue_scripts', 'carregar_arquivos');

function mostrar_mapa(){
    //Buffer para armazenar dados temporários
    ob_start(); ?>
    <head>
        <!-- Carregar ícones padrão do Leaflet do CDN -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.css" />
    </head>
    <div id="div_index">
        <div id="div-mapa">
            <div id="mapa" style="height: 500px;"></div>
            <button onclick="mostrarFormulario()">Cadastrar novo local</button>
        </div>

        <div id="formulario" style="display: none;">
            <form id="meu_formulario" method="post">
                <label for="nome" id="labelnome">Nome do local:</label>
                <input type="text" name="nome" id="nome" required><br>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required><br>
                
                <label for="descricao">Descrição:</label>
                <textarea name="descricao" id="descricao" rows="3" cols="70" placeholder="Descrição ..." required></textarea>

                <!-- Container para o mapa -->
                <div id="mapaFormulario" style="height: 300px;"></div>
                
                <!-- Input de latitude e longitude --> 
                <input type="hidden" name="latitude" id="latitude" required>
                <input type="hidden" name="longitude" id="longitude" required>

                <input type="submit" value="Enviar">
            </form>
        </div>
    </div>
    

    <!-- Inclui a biblioteca Leaflet -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Inicializa o mapa quando a página for carregada
        var map;Não foi possível recuperar os dados do formulário ou o banco de dados está vazio.


        var marcador;

        function initMap() {
            map = L.map('mapa', {doubleClickZoom: false}).setView([-15.8267, -47.9218], 13);

            // Adiciona o provedor de mapa OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            getLocation(map);
        }

        // Função para destruir o mapa
        function destroyMap() {
            map.remove();
            map = null;
        }

        // Função para mostrar o formulário e destruir o mapa
        function mostrarFormulario() {
            destroyMap();
            document.getElementById('div-mapa').style.display = 'none';
            document.getElementById('formulario').style.display = 'block';
            initMapFormulario();
        }

        // Função para inicializar o mapa no formulário
        function initMapFormulario() {

            var mapFormulario = L.map('mapaFormulario', {doubleClickZoom: false}).setView([-15.8267, -47.9218], 13);

            // Adiciona o provedor de mapa OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mapFormulario);

            getLocation(mapFormulario);

            // Adiciona um marcador no mapa quando clicado o mouse 1
            mapFormulario.on('click', function(e) {
                // Remove o marcador atual, se existir
                if (marcador) {
                    mapFormulario.removeLayer(marcador);
                }

                // Adiciona um novo marcador na posição clicada
                marcador = L.marker(e.latlng).addTo(mapFormulario);            

                var lat = e.latlng.lat; // Latitude
                var lng = e.latlng.lng; // Longitude

                // Atualiza os valores dos campos de entrada ocultos
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            });

            // Remove o marcador quando clicado com o mouse 2
            mapFormulario.on('contextmenu', function(e) {
                // Verifica se existe um marcador atual
                if (marcador) {
                    // Remove o marcador do mapa
                    mapFormulario.removeLayer(marcador);
                }
            });
        }

        // Função para obter a localização do usuário
        function getLocation(mapa) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    showPosition(position, mapa);
                });
            } else {
                alert("Geolocalização não é suportada por este navegador.");
            }
        }

        // Função para mostrar a posição do usuário no mapa
        function showPosition(position, mapa) {
            var lat = position.coords.latitude; // Latitude
            var lng = position.coords.longitude; // Longitude

            // Atualiza os valores dos campos de entrada ocultos
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Centraliza o mapa na posição do usuário
            mapa.setView([lat, lng], 13);
        }

        // Chama initMap() quando a página for carregada
        window.onload = function() {
            initMap();
        };

    </script>

    <?php
    // Saída do buffer
    return ob_get_clean();
}

