<?php
// Inclui o arquivo Auxiliar_Process_Forms.php usando um caminho relativo
require_once plugin_dir_path(__FILE__) . 'auxiliar_process_form.php';

function processar_formulario() {
    global $wpdb;

    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Transforma a string do formData em uma array
        parse_str($_POST['formData'], $formFields);

        // Filtra o conteúdo enviado nos formulários
        $nome = isset($formFields['nome']) ? filter_var($formFields['nome'], FILTER_SANITIZE_STRING) : '';
        $email = isset($formFields['email']) ? filter_var($formFields['email'], FILTER_SANITIZE_EMAIL) : '';
        $descricao = isset($formFields['descricao']) ? filter_var($formFields['descricao'], FILTER_SANITIZE_STRING) : ''; 
        $latitude = isset($formFields['latitude']) ? filter_var($formFields['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_SCIENTIFIC) : 0;
        $longitude = isset($formFields['longitude']) ? filter_var($formFields['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_SCIENTIFIC) : 0;
        $servico = isset($formFields['servico']) ? filter_var($formFields['servico'], FILTER_SANITIZE_STRING) : '';
        
        // Verifica se todos os campos necessários estão presentes
        if ($nome && $email && $descricao && $latitude && $longitude && $servico) {
            $data_hora_envio = Auxiliar_Process_Forms::get_current_time();

            // Insere os dados no banco de dados
            $result = Auxiliar_Process_Forms::insert_data_into_db($wpdb, array(
                'nome' => $nome,
                'email' => $email,
                'descricao' => $descricao,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'data_hora' => $data_hora_envio,
                'servico' => $servico
            ));

            // Obter e-mail do administrador do site
            $admin_email = Auxiliar_Process_Forms::get_admin_email();
            // Formata a data e hora para o formato desejado
            $data_hora_formatada = date('d/m/Y H:i', strtotime($data_hora_envio));

            // Constrói a URL do painel de administração
            $admin_panel_url = Auxiliar_Process_Forms::get_admin_panel_url();

            // Informações adicionais para o e-mail 
            $local_cadastrado = 'Nome do Local: ' . $nome . "\n";
            $tipo_servico = 'Tipo de Serviço: ' . $servico . "\n";
            $data_hora_cadastro = 'Data e Hora do Cadastro: ' . $data_hora_formatada . "\n";
            // Constrói a mensagem do e-mail
            $message = 'Olá! Uma nova resposta foi feita no seu formulário. Aqui estão os detalhes:' . "\n" . $local_cadastrado . $tipo_servico . $data_hora_cadastro . 'Verifique sua área de administração para mais informações: ' . $admin_panel_url;

            $subject = 'LGBTQ+ Connect - Nova solicitação de plotagem recebida';
            // Envie o e-mail de notificação para o administrador do site
            Auxiliar_Process_Forms::send_email($admin_email, $subject, $message);

            // Envie o e-mail de confirmação para o usuário
            $subject_user = 'LGBTQ+ Connect - Sua solicitação de plotagem foi recebida';
            $message_user = 'Olá! Sua solicitação de plotagem foi recebida. Aqui estão os detalhes:' . "\n" . $local_cadastrado . $tipo_servico . $data_hora_cadastro . 'Você será notificado quando sua solicitação for processada. Obrigado!';

            Auxiliar_Process_Forms::send_email($email, $subject_user, $message_user);
        } else {
            echo "Erro: Preencha todos os campos corretamente.";
        }
    }
}
?>
