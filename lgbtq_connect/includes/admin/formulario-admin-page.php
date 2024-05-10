<?php
// Função para aprovar o formulário
function aprovar_formulario($id) {
    global $wpdb;

    // Atualiza o status do formulário para 'Aprovado' no banco de dados
    $query = $wpdb->prepare("UPDATE lc_formulario SET situacao = 'Aprovado' WHERE id = %d", $id);
    $resultado = $wpdb->query($query);

    // Redireciona de volta para a mesma página após a atualização
    echo '<script>window.location.href = window.location.href;</script>';
}

function rejeitar_formulario($id) {
    global $wpdb;

    // Atualiza o status do formulário para 'Negado' no banco de dados
    $query = $wpdb->prepare("UPDATE lc_formulario SET situacao = 'Negado' WHERE id = %d", $id);
    $resultado = $wpdb->query($query);

    // Redireciona de volta para a mesma página após a atualização
    echo '<script>window.location.href = window.location.href;</script>';
}

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
                echo '<button value="aprovados" onclick="filtrarPorStatus(this)">' . count($aprovados) . ' Aprovados</button>';
                echo '<button value="negados" onclick="filtrarPorStatus(this)">' . count($negados) . ' Negados</button>';
                echo '<button value="pendentes" onclick="filtrarPorStatus(this)">' . count($pendentes) . ' Pendentes</button>';
            ?>
            </div>
        </div>
        <div id=filtros>
            <form method="post">
                <div id="busca_nome_container" class="filtro">
                    <input type="text" id="busca_nome" placeholder="Pesquise pelo nome">
                    <button>&#128270;</button>
                </div>
            </form>
            <select id="selecao_servico" class="filtro" onchange="" required>
                <option value="" selected disabled>Selecione...</option>
                <option value="bar/restaurante">Bares/restaurantes</option>
                <option value="entretenimento">Entretenimento</option>
                <option value="beleza">Beleza</option>
                <option value="hospedagem">Hospedagem</option>
                <option value="ensino">Ensino</option>
                <option value="academia">Academia</option>
                <option value="outro">Outro</option>
                <option value="todos">Todos</option>
            </select>
        </div>

        <div class="wrap">
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
            <?php
            echo '<script src="' . plugin_dir_url(__FILE__) . 'admin_script.js"></script>';
            ?>
        </div>
    </div>

    <!-- Carregue o jQuery antes de qualquer outro script que o utilize -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Carregue o Leaflet antes de qualquer script que o utilize -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  
</body>
</html>
