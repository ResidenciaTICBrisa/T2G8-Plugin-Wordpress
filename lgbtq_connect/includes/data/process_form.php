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
        $nome = isset($formFields['nome']) ? Auxiliar_Process_Forms::sanitize_data($formFields['nome'], 'text') : '';
        $email = isset($formFields['email']) ? Auxiliar_Process_Forms::sanitize_data($formFields['email'], 'email') : '';
        $descricao = isset($formFields['descricao']) ? Auxiliar_Process_Forms::sanitize_data($formFields['descricao'], 'textarea') : ''; 
        $latitude = isset($formFields['latitude']) ? Auxiliar_Process_Forms::sanitize_data($formFields['latitude'], 'float') : 0;
        $longitude = isset($formFields['longitude']) ? Auxiliar_Process_Forms::sanitize_data($formFields['longitude'], 'float') : 0;
        $servico = isset($formFields['servico']) ? Auxiliar_Process_Forms::sanitize_data($formFields['servico'], 'text') : '';

        // Verifica se todos os campos necessários estão presentes
        if ($nome && $email && $descricao && $latitude && $longitude && $servico) {
            // Obter o endereço usando a API do Nominatim com cURL
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$latitude&lon=$longitude&addressdetails=1";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            $response = curl_exec($ch);
            curl_close($ch);
            $locationData = json_decode($response, true);

            // Adicione um log para a resposta da API
            error_log(print_r($locationData, true));

            if (isset($locationData['error'])) {
                $road = 'Rua não encontrada';
                $city = 'Cidade não encontrada';
            } else {
                $road = isset($locationData['address']['road']) ? $locationData['address']['road'] : 
                        (isset($locationData['address']['pedestrian']) ? $locationData['address']['pedestrian'] : 'Rua não encontrada');
                $city = isset($locationData['address']['city']) ? $locationData['address']['city'] : 
                        (isset($locationData['address']['town']) ? $locationData['address']['town'] : 
                        (isset($locationData['address']['village']) ? $locationData['address']['village'] : 'Cidade não encontrada'));
            }

            $data_hora_envio = Auxiliar_Process_Forms::get_current_time();

            // Insere os dados no banco de dados
            $result = Auxiliar_Process_Forms::insert_data_into_db($wpdb, array(
                'nome' => $nome,
                'email' => $email,
                'descricao' => $descricao,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'road' => $road,
                'city' => $city,
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
            $endereco_informado = 'Rua: ' . $road . "\n" . 'Cidade: ' . $city . "\n";
            // Constrói a mensagem do e-mail
            $message = 'Olá! Uma nova resposta foi feita no seu formulário. Aqui estão os detalhes:' . "\n" . $local_cadastrado . $tipo_servico . $data_hora_cadastro . $endereco_informado . 'Verifique sua área de administração para mais informações: ' . $admin_panel_url;

            $subject = 'LGBTQ+ Connect - Nova solicitação de plotagem recebida';
            // Envie o e-mail de notificação para o administrador do site
            Auxiliar_Process_Forms::send_email($admin_email, $subject, $message);

            // Envie o e-mail de confirmação para o usuário
            $subject_user = 'LGBTQ+ Connect - Sua solicitação de plotagem foi recebida';
            $message_user = 'Olá! Sua solicitação de plotagem foi recebida. Aqui estão os detalhes:' . "\n" . $local_cadastrado . $tipo_servico . $data_hora_cadastro . $endereco_informado . 'Você será notificado quando sua solicitação for processada. Obrigado!';

            Auxiliar_Process_Forms::send_email($email, $subject_user, $message_user);
        } else {
            echo "Erro: Preencha todos os campos corretamente.";
        }
    }
}
?>
