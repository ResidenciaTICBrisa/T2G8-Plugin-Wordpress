<?php
global $wpdb;
$conseguir_rua_e_cidade = function ($latitude, $longitude) {
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$latitude&lon=$longitude&addressdetails=1";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    $response = curl_exec($ch);
    curl_close($ch);
    $locationData = json_decode($response, true);

    // Adicione um log para a resposta da API
    error_log("Resposta da API Nominatim: " . print_r($locationData, true));

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

    return array($rua, $cidade);
};

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'update_form') {
    error_log("Chamando a função atualizar_formulario");
    atualizar_formulario($wpdb, $conseguir_rua_e_cidade);
}

function encontrar_pagina_com_shortcode($shortcode) {
    $paginas = get_pages();
    $postagens = get_posts();
    $todos_itens = array_merge($paginas, $postagens);

    foreach ($todos_itens as $item) {
        $conteudo = $item->post_content;
        if (strpos($conteudo, $shortcode) !== false) {
            error_log("Shortcode encontrado na página/postagem ID: " . $item->ID);
            return $item;
        }
    }

    error_log("Shortcode não encontrado em nenhuma página ou postagem");
    return false;
}

function aprovar_formulario($id) {
    global $wpdb;

    error_log("Iniciando a função aprovar_formulario com ID: " . $id);

    if (!$id) {
        error_log("ID não foi passado para aprovar_formulario");
        return;
    }

    $estado_atual = $wpdb->get_var($wpdb->prepare("SELECT situacao FROM lc_formulario WHERE id = %d", $id));
    error_log("Estado atual do formulário: " . $estado_atual);

    if (!$estado_atual) {
        error_log("Estado atual não encontrado para o formulário ID: " . $id);
        return;
    }

    $atualizacao_sucesso = alteraStatus($wpdb, $id, 'Aprovado');
    error_log("Resultado da atualização de status: " . ($atualizacao_sucesso ? "Sucesso" : "Falha"));

    if ($atualizacao_sucesso && ($estado_atual === 'Negado' || $estado_atual === 'Pendente')) {
        $formulario = $wpdb->get_row($wpdb->prepare("SELECT * FROM lc_formulario WHERE id = %d", $id));
        error_log("Dados do formulário: " . print_r($formulario, true));

        if (!$formulario) {
            error_log("Formulário não encontrado para ID: " . $id);
            return;
        }

        $item = encontrar_pagina_com_shortcode('lgbtq_connect');

        if ($item) {
            $shortcode_url = get_permalink($item);
            $subject = 'Seu formulário foi aprovado';
            $message = '
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
                    <h3 style="color: #28a745;">Formulário Aprovado</h3>
                    <p>Olá!</p>
                    <p>Seu pedido de plotagem para o local <strong>' . esc_html($formulario->nome) . '</strong> foi aprovado!</p>
                    <p>Para mais informações sobre a plotagem  de <strong> ' . esc_html($formulario->nome) . '</strong> , acesse o link: <a href="' . esc_url($shortcode_url) . '" style="color: #007bff;">' . esc_url($shortcode_url) . '</a></p>
                    <hr>
                    <p>Obrigado por utilizar nosso serviço!</p>
                </div>
            </body>
            </html>';

            $headers = array('Content-Type: text/html; charset=UTF-8');

            // Log do e-mail do administrador
            $email_admin = get_option('admin_email');
            error_log("Enviando e-mail para: " . $email_admin);

            // Envie o e-mail de notificação para o usuário
            wp_mail($formulario->email, $subject, $message, $headers);
        } else {
            error_log("Página ou postagem com shortcode não encontrada");
        }
    }

    echo '<script>window.location.href = window.location.href;</script>';
}

function alteraStatus($wpdb, $id, $newStatus){
    if (!isset($wpdb) || empty($id) || empty($newStatus)) {
        error_log("Parâmetros inválidos em alteraStatus");
        return false;
    }

    $query = $wpdb->prepare("UPDATE lc_formulario SET situacao = %s WHERE id = %d", $newStatus, $id);
    $resultado = $wpdb->query($query);

    error_log("Resultado da query de atualização de status: " . ($resultado !== false ? "Sucesso" : "Falha"));

    return $resultado !== false;
}

function rejeitar_formulario($id) {
    global $wpdb;

    $estado_atual = $wpdb->get_var($wpdb->prepare("SELECT situacao FROM lc_formulario WHERE id = %d", $id));
    error_log("Estado atual do formulário para rejeição: " . $estado_atual);

    alteraStatus($wpdb, $id, 'Negado');

    if ($estado_atual === 'Pendente') {
        $formulario = $wpdb->get_row($wpdb->prepare("SELECT * FROM lc_formulario WHERE id = %d", $id));
        error_log("Dados do formulário rejeitado: " . print_r($formulario, true));

        $subject = 'Seu formulário foi rejeitado';
        $message = '
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
                <h3 style="color: #dc3545;">Formulário Rejeitado</h3>
                <p>Olá,</p>
                <p>Infelizmente, seu formulário para o cadastro de <strong>' . esc_html($formulario->nome) . '</strong> foi rejeitado.</p>
                <p>Se você acredita que houve um engano na decisão sobre <strong>' . esc_html($formulario->nome) . '</strong> , entre em contato conosco.</p>
                <hr>
            </div>
        </body>
        </html>';

        $headers = array('Content-Type: text/html; charset=UTF-8');

        // Log do e-mail do administrador
        $email_admin = get_option('admin_email');
        error_log("Enviando e-mail para: " . $email_admin);

        wp_mail($formulario->email, $subject, $message, $headers);
    }

    echo '<script>window.location.href = window.location.href;</script>';
}

function excluir_formulario($id) {
    global $wpdb;

    $resultado_exclusao = $wpdb->delete('lc_formulario', array('id' => $id));
    error_log("Resultado da exclusão do formulário ID: " . $id . " -> " . ($resultado_exclusao ? "Sucesso" : "Falha"));

    if ($resultado_exclusao === false) {
        echo '<div class="error"><p>Erro ao excluir o registro!</p></div>';
    } else {
        echo '<div class="updated"><p>Registro excluído com sucesso!</p></div>';
    }

    echo '<script>window.location.href = window.location.href;</script>';
}

function atualizar_formulario($wpdb, $funcao_localizacao) {
    if (!isset($_POST['id'], $_POST['nome'], $_POST['email'], $_POST['servico'], $_POST['descricao'], $_POST['latitude'], $_POST['longitude'])) {
        error_log("Dados insuficientes no POST para atualizar o formulário.");
        return;
    }

    $id = $_POST['id'];
    $nome = sanitize_text_field($_POST['nome']);
    $email = sanitize_email($_POST['email']);
    $servico = sanitize_text_field($_POST['servico']);
    $descricao = sanitize_textarea_field($_POST['descricao']);
    $latitude = sanitize_text_field($_POST['latitude']);
    $longitude = sanitize_text_field($_POST['longitude']);

    list($rua, $cidade) = $funcao_localizacao($latitude, $longitude);

    error_log("Atualizando formulário ID: " . $id . " com dados: Nome=" . $nome . ", E-mail=" . $email . ", Serviço=" . $servico);

    $resultado = $wpdb->update(
        'lc_formulario',
        array(
            'nome' => $nome,
            'email' => $email,
            'rua' => $rua,
            'cidade' => $cidade,
            'servico' => $servico,
            'descricao' => $descricao,
            'latitude' => $latitude,
            'longitude' => $longitude
        ),
        array('id' => $id),
        array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'),
        array('%d')
    );

    if ($resultado !== false) {
        echo '<div class="updated"><p>Formulário atualizado com sucesso!</p></div>';
    } else {
        echo '<div class="error"><p>Erro ao atualizar o formulário.</p></div>';
    }

    echo '<script>window.location.href = window.location.href;</script>';
}
?>
