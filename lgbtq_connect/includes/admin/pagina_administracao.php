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

// Função para mostrar os dados na página do painel de administração
function mostrar_dados() {
    global $wpdb;
    // Consulta os dados da tabela formulario
    $dados_formulario = $wpdb->get_results("SELECT * FROM lc_formulario");
    
    require 'formulario-admin-page.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                echo '<div class="button-container">';
                    echo '<button value="Pendente" class="btn-pendente" onclick="filtrar(this)">' . count($pendentes) . ' Pendentes</button>';
                    echo '<button value="Negado" class="btn-negado" onclick="filtrar(this)">' . count($negados) . ' Negados</button>';
                    echo '<button value="Aprovado" class=" btn-aprovado" onclick="filtrar(this)">' . count($aprovados) . ' Aprovados</button>';
                echo '</div>';        
            ?>
            </div>
        </div>
        <div id="contador_resultados">
        </div>
        <div id="filtros" >
            <form method="post">
                <div id="busca_nome_container" class="filtro">
                    <input type="text" id="busca_nome" placeholder="Pesquise pelo nome" oninput="filtrar()">
                </div>
            </form>
            <select id="selecao_servico" class="filtro " onchange="filtrar()" required>
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
            <div class="container mt-5">
            <table class="table table-hover" id="tabela">
                <thead class="thead-light">
                    <tr>
                        <th class="sort-header" scope="col">Nome <button class="sort-btn" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>
                        <th class="sort-header" scope="col">Email <button class="sort-btn sort-by-email" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>
                        <th scope="col">Latitude</th>
                        <th scope="col">Longitude</th>
                        <th scope="col">Serviço</th>
                        <th scope="col">Descrição</th>
                        <th class="sort-header" scope="col">Data e hora <button class="sort-btn sort-by-date" data-order="asc"><span class="sort-icon">&#9652;</span></button></th>
                        <th scope="col">Status</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Adicione aqui as linhas da tabela -->
                </tbody>
            </table>
    </div>
            
        </div>
    </div>

    <!-- Carregue o jQuery antes de qualquer outro script que o utilize -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Carregue o Leaflet antes de qualquer script que o utilize -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  
</body>
</html>
<?php
}