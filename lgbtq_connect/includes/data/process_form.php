<?php
function processar_formulario() {

    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        # Transforma a string do formData em uma array
        parse_str($_POST['formData'], $formFields);

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
            // Obter e-mail do administrador do site
            $admin_email = get_option('admin_email');
            // Formata a data e hora para o formato desejado
            $data_hora_formatada = date('d/m/Y H:i', strtotime($data_hora_envio));

            // Constrói a URL do painel de administração
            $admin_panel_url = admin_url('admin.php?page=lc_admin');

            // Informações adicionais para o e-mail 
            $local_cadastrado = 'Nome do Local: ' . $nome . "\n";
            $tipo_servico = 'Tipo de Serviço: ' . $servico . "\n";
            $data_hora_cadastro = 'Data e Hora do Cadastro: ' . $data_hora_formatada . "\n";
            // Constrói a mensagem do e-mail
            $message = 'Olá! Uma nova resposta foi feita no seu formulário. Aqui estão os detalhes:' . "\n" . $local_cadastrado . $tipo_servico . $data_hora_cadastro . 'Verifique sua área de administração para mais informações: ' . $admin_panel_url;

            $subject = 'LGBTQ+ Connect - Nova solicitação de plotagem recebida';
            // Envie o e-mail de notificação para o administrador do site
            wp_mail($admin_email, $subject, $message);

        } else {
            echo "Erro: Preencha todos os campos corretamente.";
        }
        
    }
}
?>
