<?php
function sanitize_data($data, $type = 'text') {
    switch ($type) {
        case 'email':
            return filter_var($data, FILTER_SANITIZE_EMAIL);
        case 'textarea':
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        case 'float':
            return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        default:
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

function insert_data_into_db($data) {
    global $wpdb;
    $table_name = "lc_formulario";
    return $wpdb->insert($table_name, $data);
}

function get_current_time() {
    return current_time('mysql');
}

function get_admin_email() {
    return get_option('admin_email');
}

function get_admin_panel_url() {
    return admin_url('admin.php?page=lc_admin');
}

function send_email($to, $subject, $message) {
    return wp_mail($to, $subject, $message);
}

function processar_formulario() {

    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Transforma a string do formData em uma array
        parse_str($_POST['formData'], $formFields);

        // Filtra o conteúdo enviado nos formulários
        $nome = isset($formFields['nome']) ? sanitize_data($formFields['nome'], 'text') : '';
        $email = isset($formFields['email']) ? sanitize_data($formFields['email'], 'email') : '';
        $descricao = isset($formFields['descricao']) ? sanitize_data($formFields['descricao'], 'textarea') : ''; 
        $latitude = isset($formFields['latitude']) ? sanitize_data($formFields['latitude'], 'float') : 0;
        $longitude = isset($formFields['longitude']) ? sanitize_data($formFields['longitude'], 'float') : 0;
        $servico = isset($formFields['servico']) ? sanitize_data($formFields['servico'], 'text') : '';

        // Verifica se todos os campos necessários estão presentes
        if ($nome && $email && $descricao && $latitude && $longitude && $servico) {
            $data_hora_envio = get_current_time();

            // Insere os dados no banco de dados
            $result = insert_data_into_db(array(
                'nome' => $nome,
                'email' => $email,
                'descricao' => $descricao,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'data_hora' => $data_hora_envio,
                'servico' => $servico
            ));

            // Obter e-mail do administrador do site
            $admin_email = get_admin_email();
            // Formata a data e hora para o formato desejado
            $data_hora_formatada = date('d/m/Y H:i', strtotime($data_hora_envio));

            // Constrói a URL do painel de administração
            $admin_panel_url = get_admin_panel_url();

            // Informações adicionais para o e-mail 
            $local_cadastrado = 'Nome do Local: ' . $nome . "\n";
            $tipo_servico = 'Tipo de Serviço: ' . $servico . "\n";
            $data_hora_cadastro = 'Data e Hora do Cadastro: ' . $data_hora_formatada . "\n";
            // Constrói a mensagem do e-mail
            $message = 'Olá! Uma nova resposta foi feita no seu formulário. Aqui estão os detalhes:' . "\n" . $local_cadastrado . $tipo_servico . $data_hora_cadastro . 'Verifique sua área de administração para mais informações: ' . $admin_panel_url;

            $subject = 'LGBTQ+ Connect - Nova solicitação de plotagem recebida';
            // Envie o e-mail de notificação para o administrador do site
            send_email($admin_email, $subject, $message);

            // Envie o e-mail de confirmação para o usuário
            $subject_user = 'LGBTQ+ Connect - Sua solicitação de plotagem foi recebida';
            $message_user = 'Olá! Sua solicitação de plotagem foi recebida. Aqui estão os detalhes:' . "\n" . $local_cadastrado . $tipo_servico . $data_hora_cadastro . 'Você será notificado quando sua solicitação for processada. Obrigado!';

            send_email($email, $subject_user, $message_user);
        } else {
            echo "Erro: Preencha todos os campos corretamente.";
        }
    }
}
?>
