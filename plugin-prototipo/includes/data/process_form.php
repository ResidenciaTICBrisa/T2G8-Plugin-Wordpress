<?php
function processar_formulario() {
    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Filtra o conteúdo enviado nos formulários
        $nome = isset($_POST['nome']) ? sanitize_text_field($_POST['nome']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $descricao = isset($_POST['descricao']) ? sanitize_textarea_field($_POST['descricao']) : '';
        $latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : 0;
        $longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : 0;

        // Verifica se todos os campos necessários estão presentes
        if ($nome && $email && $descricao && $latitude && $longitude) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'formulario';

            // Insere os dados no banco de dados
            $result = $wpdb->insert(
                $table_name,
                array(
                    'nome' => $nome,
                    'email' => $email,
                    'descricao' => $descricao,
                    'latitude' => $latitude,
                    'longitude' => $longitude
                )
            );

            
            // Redirecionar para a mesma página para evitar o reenvio de dados
            wp_redirect($_SERVER['REQUEST_URI']);
            exit;
        } else {
            echo "Erro: Preencha todos os campos corretamente.";
        }
    }
}





