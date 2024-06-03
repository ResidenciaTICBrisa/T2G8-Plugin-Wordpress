<?php
// Função para encontrar a página ou postagem que contém o shortcode do plugin
function encontrar_pagina_com_shortcode($shortcode) {
    // Obtém todas as páginas do site
    $paginas = get_pages();
    // Obtém todas as postagens do site
    $postagens = get_posts();

    // Concatena as páginas e postagens em um único array
    $todos_itens = array_merge($paginas, $postagens);

    // Percorre cada item para verificar se o conteúdo contém o shortcode
    foreach ($todos_itens as $item) {
        // Obtém o conteúdo do item
        $conteudo = $item->post_content;
        // Verifica se o conteúdo contém o shortcode
        if (strpos($conteudo, $shortcode) !== false) {
            // Se o shortcode for encontrado, retorna o item
            return $item;
        }
    }

    // Se nenhum shortcode for encontrado, retorna falso
    return false;
}
// Função para aprovar o formulário
function aprovar_formulario($id) {
    global $wpdb;

    // Busca o estado atual do formulário
    $estado_atual = $wpdb->get_var($wpdb->prepare("SELECT situacao FROM lc_formulario WHERE id = %d", $id));

    // Atualiza o status do formulário para 'Aprovado' no banco de dados
    alteraStatus($wpdb, $id, 'Aprovado');

    // Verifica se o estado anterior era 'Negado' para enviar o e-mail de notificação
    if ($estado_atual === 'Negado' || $estado_atual === 'Pendente') {
        // Busca as informações do formulário
        $formulario = $wpdb->get_row($wpdb->prepare("SELECT * FROM lc_formulario WHERE id = %d", $id));

        // Encontra o item que contém o shortcode do seu plugin
        $item = encontrar_pagina_com_shortcode('lgbtq_connect');

        // Se o item for encontrado, obtenha o permalink e construa o link
        if ($item) {
            $shortcode_url = get_permalink($item);
            // Constrói o e-mail com o link para a página do shortcode
            $subject = 'Seu formulário foi aprovado';
            $message = 'Olá! Seu pedido de plotagem para o ' . $formulario->nome . ' foi aprovado!' . "\n\n" . 'Para mais informações acesse o link: ' . $shortcode_url;

            // Envie o e-mail de notificação para o usuário
            wp_mail($formulario->email, $subject, $message);
        } else {
            // Página ou postagem com shortcode não encontrada
            // Faça o tratamento adequado aqui
        }
    }

    // Redireciona de volta para a mesma página após a atualização
    echo '<script>window.location.href = window.location.href;</script>';
}

function alteraStatus($wpdb, $id, $newStatus){
    if (!isset($wpdb) || empty($id) || empty($newStatus)) {
        return false;
    }

    // Atualiza o status do formulário no banco de dados
    $query = $wpdb->prepare("UPDATE lc_formulario SET situacao = %s WHERE id = %d", $newStatus, $id);
    $resultado = $wpdb->query($query);

    if($resultado === false) {
        // Trate o erro aqui
        return false;
    }

    return true;
}

// Função para rejeitar o formulário
function rejeitar_formulario($id) {
    global $wpdb;

    // Busca o estado atual do formulário
    $estado_atual = $wpdb->get_var($wpdb->prepare("SELECT situacao FROM lc_formulario WHERE id = %d", $id));

    alteraStatus($wpdb, $id, 'Negado');

    // Verifica se o estado anterior era 'Aprovado' ou 'Pendente' para enviar o e-mail de notificação
    if ($estado_atual === 'Aprovado' || $estado_atual === 'Pendente') {
        // Busca as informações do formulário
        $formulario = $wpdb->get_row($wpdb->prepare("SELECT * FROM lc_formulario WHERE id = %d", $id));

        // Constrói o e-mail
        $subject = 'Seu formulário foi rejeitado';
        $message = 'Olá! Infelizmente seu formulário para o cadastro de ' . $formulario->nome . ' foi rejeitado :(';

        // Envie o e-mail de notificação para o usuário
        wp_mail($formulario->email, $subject, $message);
    }

    // Redireciona de volta para a mesma página após a atualização
    echo '<script>window.location.href = window.location.href;</script>';
}
// Função para excluir o formulário
function excluir_formulario($id) {
    global $wpdb;

    // Executa a consulta para excluir o registro
    $resultado_exclusao = $wpdb->delete('lc_formulario', array('id' => $id));

    // Exibe uma mensagem de sucesso ou erro
    if ($resultado_exclusao === false) {
        echo '<div class="error"><p>Erro ao excluir o registro!</p></div>';
    } else {
        echo '<div class="updated"><p>Registro excluído com sucesso!</p></div>';
    }

    // Redireciona de volta paloucademia de policiara a mesma página após a atualização
    echo '<script>window.location.href = window.location.href;</script>';
}