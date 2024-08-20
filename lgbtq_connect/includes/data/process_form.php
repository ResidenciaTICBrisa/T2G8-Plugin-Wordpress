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
                // Obter o endereço usando a API do Nominatim com cURL
                $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$latitude&lon=$longitude&addressdetails=1";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                $response = curl_exec($ch);
                curl_close($ch);
                $locationData = json_decode($response, true);

                // Adicione um log para a resposta da API
                error_log(print_r($locationData, true));

                if (isset($locationData['error'])) {
                    $rua = 'Rua não encontrada';
                    $cidade = 'Cidade não encontrada';
                } else {
                    $rua = isset($locationData['address']['road']) ? $locationData['address']['road'] : 
                            (isset($locationData['address']['pedestrian']) ? $locationData['address']['pedestrian'] : 'Rua não encontrada');
                    $cidade = isset($locationData['address']['city']) ? $locationData['address']['city'] : 
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
                    'rua' => $rua,
                    'cidade' => $cidade,
                    'data_hora' => $data_hora_envio,
                    'servico' => $servico
                ));

                // Obter e-mail do administrador do site
                $admin_email = Auxiliar_Process_Forms::get_admin_email();
                // Formata a data e hora para o formato desejado
                $data_hora_formatada = date('d/m/Y H:i', strtotime($data_hora_envio));

                // Constrói a URL do painel de administração
                $admin_panel_url = Auxiliar_Process_Forms::get_admin_panel_url();

                // Constrói a mensagem HTML para o e-mail do administrador
                $message_admin = '
                <html>
                <body style="font-family: Arial, sans-serif; color: #333;">
                    <div style="max-width: 600px; margin: auto; border: 1px solid #ccc; padding: 20px;">
                        <h2 style="font-family: Arial, sans-serif; font-weight: bold;">
                            <span style="color: #FF0000;">L</span>
                            <span style="color: #FF7F00;">G</span>
                            <span style="color: #FFFF00;">B</span>
                            <span style="color: #00FF00;">T</span>
                            <span style="color: #0000FF;">Q</span>
                            <span style="color: #4B0082;">+</span>
                            <span style="color: #8B00FF;"> </span>
                            <span style="color: #8B00FF;"> </span>
                            <span style="color: #FF0000;">C</span>
                            <span style="color: #FF7F00;">o</span>
                            <span style="color: #FFFF00;">n</span>
                            <span style="color: #00FF00;">n</span>
                            <span style="color: #0000FF;">e</span>
                            <span style="color: #4B0082;">c</span>
                            <span style="color: #8B00FF;">t</span>
                        </h2>
                        <h3 style="color: #007bff;">Nova Solicitação de Plotagem Recebida</h3>
                        <p>Olá! Uma nova resposta foi feita no seu formulário. Aqui estão os detalhes:</p>
                        <p><strong>Nome do Local:</strong> ' . esc_html($nome) . '</p>
                        <p><strong>Tipo de Serviço:</strong> ' . esc_html($servico) . '</p>
                        <p><strong>Data e Hora do Cadastro:</strong> ' . esc_html($data_hora_formatada) . '</p>
                        <p><strong>Rua:</strong> ' . esc_html($rua) . '</p>
                        <p><strong>Cidade:</strong> ' . esc_html($cidade) . '</p>
                        <p>Verifique sua área de administração para mais informações sobre <strong>' . esc_html($nome) . '</strong></p> : <a href="' . esc_url($admin_panel_url) . '" style="color: #007bff;">' . esc_url($admin_panel_url) . '</a></p>
                        <hr>
                        <p>Obrigado por utilizar nosso serviço!</p>
                    </div>
                </body>
                </html>';

                $subject_user = 'LGBTQ+ Connect - Sua solicitação de plotagem foi recebida';
                $message_user = '
                <html>
                <body style="font-family: Arial, sans-serif; color: #333;">
                    <div style="max-width: 600px; margin: auto; border: 1px solid #ccc; padding: 20px;">
                        <h2 style="font-family: Arial, sans-serif; font-weight: bold;">
                            <span style="color: #FF0000;">L</span>
                            <span style="color: #FF7F00;">G</span>
                            <span style="color: #FFFF00;">B</span>
                            <span style="color: #00FF00;">T</span>
                            <span style="color: #0000FF;">Q</span>
                            <span style="color: #4B0082;">+</span>
                            <span style="color: #8B00FF;"> </span>
                            <span style="color: #8B00FF;"> </span>
                            <span style="color: #FF0000;">C</span>
                            <span style="color: #FF7F00;">o</span>
                            <span style="color: #FFFF00;">n</span>
                            <span style="color: #00FF00;">n</span>
                            <span style="color: #0000FF;">e</span>
                            <span style="color: #4B0082;">c</span>
                            <span style="color: #8B00FF;">t</span>
                        </h2>
                        <h3 style="font-family: Arial, sans-serif; font-weight: bold;">
                            Sua Solicitação de Plotagem foi Recebida
                        </h3>
                        <p>Olá! Sua solicitação de plotagem foi recebida. Aqui estão os detalhes:</p>
                        <p><strong>Nome do Local:</strong> ' . esc_html($nome) . '</p>
                        <p><strong>Tipo de Serviço:</strong> ' . esc_html($servico) . '</p>
                        <p><strong>Data e Hora do Cadastro:</strong> ' . esc_html($data_hora_formatada) . '</p>
                        <p><strong>Rua:</strong> ' . esc_html($rua) . '</p>
                        <p><strong>Cidade:</strong> ' . esc_html($cidade) . '</p>
                        <p>Assim que a decisão sobre a plotagem ou não de <strong>' . esc_html($nome) . '</strong> ,você será notificado aqui no seu e-mail mesmo. Obrigado!</p>
                    </div>
                </body>
                </html>';

                // Cabeçalho para enviar como HTML
                $headers = array('Content-Type: text/html; charset=UTF-8');

                // Envie o e-mail de notificação para o administrador do site
                wp_mail($admin_email, 'LGBTQ+ Connect - Nova solicitação de plotagem recebida', $message_admin, $headers);

                // Envie o e-mail de confirmação para o usuário
                wp_mail($email, $subject_user, $message_user, $headers);

                // Retorna sucesso na solicitação AJAX
                echo 'success';
            } else {
                // Retorna erro se os campos obrigatórios não estiverem preenchidos
                echo 'error';
            }
        }
        wp_die();
    }
