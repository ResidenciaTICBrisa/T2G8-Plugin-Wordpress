<?php
/*
Plugin Name: LGBTQ+ Connect
Description: Adiciona um formulário simples a uma página WordPress com um mapa Leaflet.
Version: 0.2.0
Author: Will Bernardo, Igor Brandão, Max Rohrer e Gustavo Linhares
*/

// Adiciona o shortcode [meu_formulario] para mostrar o formulário
add_shortcode('meu_formulario', 'mostrar_formulario');

// Adiciona a conexão com banco de dados
include_once('conexao_bd.php');


// Função para carregar os estilos CSS
function carregar_estilos() {
    // Registra o arquivo CSS
    wp_register_style('meu_formulario_css', plugins_url('style_form.css', __FILE__));
    // Enfila o arquivo CSS registrado
    wp_enqueue_style('meu_formulario_css');

}
// Adiciona um gancho para carregar os estilos
add_action('wp_enqueue_scripts', 'carregar_estilos');

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

    <!-- Script para inicializar o mapa -->
    <script>
        // Inicializa o mapa
        var map = L.map('mapa', {doubleClickZoom: false}).setView([-15.8267, -47.9218], 10);
        
        // Adiciona o provedor de mapa OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Variável global para armazenar o marcador atual
        var marcador;

        // Função para obter a localização do usuário
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocalização não é suportada por este navegador.");
            }
        }

        // Função para mostrar a posição do usuário no mapa
        function showPosition(position) {
            var lat = position.coords.latitude; // Latitude
            var lng = position.coords.longitude; // Longitude

            // Remove o marcador atual, se existir
            if (marcador) {
                map.removeLayer(marcador);
            }

            // Atualiza os valores dos campos de entrada ocultos
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Centraliza o mapa na posição do usuário
            map.setView([lat, lng], 17);
        }

        // Chama getLocation() quando a página for carregada para obter a localização do usuário automaticamente
        window.onload = getLocation;

        // Adiciona um pin no mapa quando clicado o mouse 1
        map.on('click', function(e) {
            // Remove o marcador atual, se existir
            if (marcador) {
                map.removeLayer(marcador);
            }

            // Adiciona um novo marcador na posição clicada
            marcador = L.marker(e.latlng).addTo(map);            

            var lat = e.latlng.lat; // Latitude
            var lng = e.latlng.lng; // Longitude

            // Atualiza os valores dos campos de entrada ocultos
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });

        // Remove o marcador quando clicado com o mouse 2
        map.on('contextmenu', function(e) {
            // Verifica se existe um marcador atual
            if (marcador) {
                // Remove o marcador do mapa
                map.removeLayer(marcador);
            }
        });
    </script>

    <?php
    // Saída do buffer
    return ob_get_clean();
}

// Função para processar o formulário
function processar_formulario() {
    if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['descricao'])) {
        
        // Filtrando o conteúdo enviado nos formulários
        $nome = sanitize_text_field($_POST['nome']);
        $email = sanitize_email($_POST['email']);
        $descricao = sanitize_textarea_field($_POST['descricao']);

        // enviar email
        $para = $email;
        $assunto = 'Confirmação de envio do formulário';
        $mensagem = 'Olá ' . $nome . ', seu formulário foi enviado com sucesso!';
        wp_mail($para, $assunto, $mensagem);
        
    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verifica se todos os campos necessários estão presentes
        if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['descricao'])) {
            
            // Obtém as informações de conexão com o banco de dados do WordPress
            $informacoes_bd = obter_informacoes_bd_wordpress();

            // Verifica se foi possível obter as informações de conexão
            if ($informacoes_bd) {
                // Conecta ao banco de dados
                $conexao = mysqli_connect($informacoes_bd['host'], $informacoes_bd['usuario'], $informacoes_bd['senha'], $informacoes_bd['nome_bd']);

                // Verifica a conexão
                if (!$conexao) {
                    die('Erro de conexão com o banco de dados: ' . mysqli_connect_error());
                }

                // Limpa e obtém os dados do formulário
                $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
                $email = mysqli_real_escape_string($conexao, $_POST['email']);
                $latitude = mysqli_real_escape_string($conexao, $_POST['latitude']);
                $longitude = mysqli_real_escape_string($conexao, $_POST['longitude']);
                $descricao = mysqli_real_escape_string($conexao, $_POST['descricao']);

                // Prepara e executa a consulta SQL de inserção
                $sql = "INSERT INTO formulario (nome, email, latitude, longitude, descricao) VALUES ('$nome', '$email', '$latitude', '$longitude', '$descricao')";
                if (mysqli_query($conexao, $sql)) {
                    echo "Dados inseridos com sucesso!";
                } else {
                    echo "Erro ao inserir dados: " . mysqli_error($conexao);
                }

                // Fecha a conexão com o banco de dados
                mysqli_close($conexao);
            } else {
                echo "Não foi possível obter as informações de conexão com o banco de dados do WordPress.";
            }
        } else {
            echo "Todos os campos do formulário são obrigatórios.";
        }
    }
    // Você pode adicionar mais ações aqui, como redirecionar o usuário para uma página de agradecimento
}
}

// Adiciona um gancho para processar o formulário quando o WordPress estiver processando solicitações
add_action('init', 'processar_formulario');
