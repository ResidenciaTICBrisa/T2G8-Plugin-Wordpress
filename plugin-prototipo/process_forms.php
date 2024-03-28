<?php
// Adiciona a conexão com banco de dados
include_once('conexao_bd.php');

// Função para processar o formulário
function processar_formulario() {
    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Filtra o conteúdo enviado nos formulários
        $nome = isset($_POST['nome']) ? sanitize_text_field($_POST['nome']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $descricao = isset($_POST['descricao']) ? sanitize_textarea_field($_POST['descricao']) : '';

        // Verifica se todos os campos necessários estão presentes
        if ($nome && $email && isset($_POST['latitude']) && isset($_POST['longitude']) && $descricao) {
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
                    // Redirecionar para a mesma página para evitar o reenvio de dados
                    wp_redirect($_SERVER['REQUEST_URI']);
                    exit;
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
}?>