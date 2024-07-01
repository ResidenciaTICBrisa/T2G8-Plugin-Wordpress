<?php
// Adiciona uma página ao menu do painel de administração
function adicionar_pagina_administracao() {
    add_menu_page(
        'LGBTQ+ Connect', // Título da página de administração
        'LGBTQ+ Connect', // Título do menu do painel de administração
        'manage_options',
        'lc_admin',
        'mostrar_dados',
        'dashicons-admin-users',
        6
    );
}

add_action('admin_menu', 'adicionar_pagina_administracao');

require 'formulario-admin-page.php';

// Função para mostrar os dados na página do painel de administração
function mostrar_dados() {
    global $wpdb;

     // Exibir mensagem de sucesso se os dados foram atualizados
     if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo '<div class="notice notice-success is-dismissible"><p>Formulário atualizado com sucesso!</p></div>';
    }

    // Consulta os dados da tabela formulario
    $dados_formulario = $wpdb->get_results("SELECT * FROM lc_formulario");
    
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
</head>
<body>
    <div id="div_admin"> 
        <!-- Modal de Edição -->
        <div id="editPopup" style="display:none;"></div>
        <div id="editModal" style="display:none;">
            <button type="button" id="editFechar" onclick="fecharEditor()">
                <svg height="30px" id="Layer_1" style="enable-background:new 0 0 512 512; cursor: pointer;" version="1.1" viewBox="0 0 512 512" width="30px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <path d="M443.6,387.1L312.4,255.4l131.5-130c5.4-5.4,5.4-14.2,0-19.6l-37.4-37.6c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4  L256,197.8L124.9,68.3c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4L68,105.9c-5.4,5.4-5.4,14.2,0,19.6l131.5,130L68.4,387.1  c-2.6,2.6-4.1,6.1-4.1,9.8c0,3.7,1.4,7.2,4.1,9.8l37.4,37.6c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1L256,313.1l130.7,131.1  c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1l37.4-37.6c2.6-2.6,4.1-6.1,4.1-9.8C447.7,393.2,446.2,389.7,443.6,387.1z"/>
                </svg>
            </button>
            <div class="title">Editar os dados do formulário<br></div>
            <form id="editForm" method="post" action="<?php echo admin_url('admin.php?page=lc_admin'); ?>">
                <input type="hidden" name="id" id="editId">
                <input type="hidden" name="action" value="update_form">

                <label for="nome" id="editLabelNome">Nome do local:</label>
                <input type="text" name="nome" id="editNome" required><br>

                <label for="email" id="editLabelEmail">Email:</label>
                <input type="email" name="email" id="editEmail" required><br>

                <label for="servico" id="editLabelServico">Tipo de serviço:</label><br>
                <input type="text" name="servico" id="editServico" maxlength="30" minlength="3">

                <label for="descricao" id="editLabelDescricao">Descrição:</label>
                <textarea name="descricao" id="editDescricao" rows="3" cols="70" placeholder="Descrição ..." required></textarea>

                <div class="search_wrapper">
                    <input type="text" id="searchInputFormEdit" placeholder="Pesquise a cidade ou estado...">
                    <button type="button" onclick="return searchButtonClickedEdit()" class="button_search" style="cursor: pointer;">Pesquisar</button>
                </div>

                <div id="listaResultadosEdit"></div>
                    
                <div id="mapa_formulario_edit" style="height: 300px;margin-bottom:10px; border-radius:10px;"></div>
                <input type="hidden" name="latitude" id="editLatitude" required>
                <input type="hidden" name="longitude" id="editLongitude" required>

                <div id=editDivBotoes>
                    <button type="button" id="editCancelar" onclick="fecharEditor()" style="cursor: pointer;">Cancelar</button>
                    <button type="submit" action="atualizar_formulario($wpdb)" id="editSalvar" style="cursor: pointer;">Salvar</button>
                </div>
            </form>
        </div>
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
        <div id="contador_resultados"></div>
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
                        <th class="sort-header">Nome <button class="sort-btn" data-order="asc" onclick="ordenar(this)"><span class="sort-icon">&#9652;</span></button></th>
                        <th class="sort-header">Email <button class="sort-btn sort-by-email" data-order="asc" onclick="ordenar(this)"><span class="sort-icon">&#9652;</span></button></th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Serviço</th>
                        <th>Descrição</th>
                        <th class="sort-header">Data e hora <button class="sort-btn sort-by-date" data-order="asc" onclick="ordenar(this)"><span class="sort-icon">&#9652;</span></button></th>
                        <th>Status</th>
                        <th>Ações</th>
                        </tr>
                </thead>
                <tbody>
                </tbody>
        </div>
    </div>

    <!-- Carregue o jQuery antes de qualquer outro script que o utilize -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Carregue o Leaflet antes de qualquer script que o utilize -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  
</body>
</html>
<?php
}