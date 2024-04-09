<?php
function processar_formulario() {

    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        # Transforma a string do formData em uma array
        parse_str($_POST['formData'], $formFields);
        foreach($formFields as $a){
            echo $a;
        }

        // Filtra o conteúdo enviado nos formulários
        $nome = isset($formFields['nome']) ? sanitize_text_field($formFields['nome']) : '';
        $email = isset($formFields['email']) ? sanitize_email($formFields['email']) : '';
        $descricao = isset($formFields['descricao']) ? sanitize_textarea_field($formFields['descricao']) : ''; 
        $latitude = isset($formFields['latitude']) ? floatval($formFields['latitude']) : 0;
        $longitude = isset($formFields['longitude']) ? floatval($formFields['longitude']) : 0;
        $servico = isset($formFields['servico']) ? $formFields['servico'] : '';

        // Verifica se todos os campos necessários estão presentes
        if ($nome && $email && $descricao && $latitude && $longitude && $servico) {
            global $wpdb;

            $table_name = "lc_formulario";

            $data_hora_envio = current_time('mysql');

            // Insere os dados no banco de dados
            $result = $wpdb->insert(
                $table_name,
                array(
                    'nome' => $nome,
                    'email' => $email,
                    'descricao' => $descricao,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'data_hora' => $data_hora_envio,
                    'servico' => $servico
                )
            );
        
        } else {
            echo "Erro: Preencha todos os campos corretamente.";
        }
    }

}