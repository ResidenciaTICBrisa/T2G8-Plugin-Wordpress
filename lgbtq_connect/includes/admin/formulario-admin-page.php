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
    $query = $wpdb->prepare("UPDATE lc_formulario SET situacao = 'Aprovado' WHERE id = %d", $id);
    $resultado = $wpdb->query($query);

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
// Função para rejeitar o formulário
function rejeitar_formulario($id) {
    global $wpdb;

    // Busca o estado atual do formulário
    $estado_atual = $wpdb->get_var($wpdb->prepare("SELECT situacao FROM lc_formulario WHERE id = %d", $id));

    // Atualiza o status do formulário para 'Negado' no banco de dados
    $query = $wpdb->prepare("UPDATE lc_formulario SET situacao = 'Negado' WHERE id = %d", $id);
    $resultado = $wpdb->query($query);

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
// Verifica se o parâmetro "action" foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    // Verifica a ação do formulário
    if ($_POST['action'] === 'approve' && isset($_POST['id'])) {
        aprovar_formulario($_POST['id']);
    } 
    elseif ($_POST['action'] === 'reprove' && isset($_POST['id'])) {
        rejeitar_formulario($_POST['id']);
    }
    elseif ($_POST['action'] === 'exclude' && isset($_POST['id'])) {
        excluir_formulario($_POST['id']);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Linkando css -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>style-admin.css">
</head>
<body>
    <div id="div_admin"> 
        <div id="div-mapa_botoes">
            <div id="mapa_admin" class="div-mapa_botoes_filho" style="height: 300px; width: 60%; margin-bottom: 10px;"></div>
            <div id="botoes_admin" class="div-mapa_botoes_filho" style="width: 30%; margin-bottom: 10px;">
            <?php
                global $wpdb;

                $query_aprovados = "SELECT * FROM lc_formulario WHERE situacao='Aprovado'";
                $query_negados = "SELECT * FROM lc_formulario WHERE situacao='Negado'";
                $query_pendentes = "SELECT * FROM lc_formulario WHERE situacao='Pendente'";

                $aprovados = $wpdb->get_results($query_aprovados);
                $negados = $wpdb->get_results($query_negados);
                $pendentes = $wpdb->get_results($query_pendentes);
                echo '<button value="Aprovado" onclick="filtrar(this)">' . count($aprovados) . ' Aprovados</button>';
                echo '<button value="Negado" onclick="filtrar(this)">' . count($negados) . ' Negados</button>';
                echo '<button value="Pendente" onclick="filtrar(this)">' . count($pendentes) . ' Pendentes</button>';
            ?>
            </div>
        </div>
        <div id="contador_resultados">
        </div>
        <div id=filtros>
            <form method="post">
                <div id="busca_nome_container" class="filtro">
                    <input type="text" id="busca_nome" placeholder="Pesquise pelo nome" oninput="filtrar()">
                </div>
            </form>
            <select id="selecao_servico" class="filtro" onchange="filtrar()" required>
                <option value="" selected disabled>Selecione...</option>
                <option value="bar/restaurante">Bares/restaurantes</option>
                <option value="entretenimento">Entretenimento</option>
                <option value="beleza">Beleza</option>
                <option value="hospedagem">Hospedagem</option>
                <option value="ensino">Ensino</option>
                <option value="academia">Academia</option>
                <option value="">Todos</option>
            </select>
        </div>
        <div class="wrap">
        <div id="confirmModal" class="modal">
                <div class="modal-content">
                    <p id="confirmMessage"></p>
                    <button id="confirmBtn" onclick=>Confirmar</button>
                    <button id="cancelBtn">Cancelar</button>
                </div>
            </div>
            <table class="wp-list-table widefat striped" id="tabela">
                <thead>
                        <tr>
                        <th class="sort-header">Nome <button class="sort-btn" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>
                        <th class="sort-header">Email <button class="sort-btn sort-by-email" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Serviço</th>
                        <th>Descrição</th>
                        <th class="sort-header">Data e hora <button class="sort-btn sort-by-date" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>
                        <th>Status</th>
                        <th>Ações</th>
                        </tr>
                </thead>
                <tbody>
        </div>
    </div>

    <!-- Carregue o jQuery antes de qualquer outro script que o utilize -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Carregue o Leaflet antes de qualquer script que o utilize -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  
</body>
</html>
