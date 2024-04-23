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

// Verifica se o parâmetro "action" foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    // Verifica a ação do formulário
    if ($_POST['action'] === 'approve' && isset($_POST['id'])) {
        aprovar_formulario($_POST['id']);
    } elseif ($_POST['action'] === 'reprove' && isset($_POST['id'])) {
        rejeitar_formulario($_POST['id']);
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
    <div id="mapa_admin" style="height: 400px; margin-bottom: 10px;"></div>
    <div class="wrap">
        <?php
        // Definições das categorias de formulários
        $categorias = [
            'Pendente' => 'Formulários Pendentes',
            'Aprovado' => 'Formulários Aprovados',
            'Negado' => 'Formulários Negados'
        ];

        // Consulta os dados da tabela formulario
        global $wpdb;

        // Parâmetros para ordenação (nome e direção)
        $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'nome'; // Coluna padrão para ordenação é o nome
        $order = isset($_GET['order']) ? $_GET['order'] : 'asc'; // Ordem padrão é crescente

        // Monta a consulta SQL com base nos parâmetros de ordenação
        $query = "SELECT * FROM lc_formulario ORDER BY $order_by $order";
        $dados_formulario = $wpdb->get_results($query);

        // Itera sobre cada categoria de formulários
        foreach ($categorias as $situacao => $titulo) {
            // Filtra os dados pelo status (situacao)
            $formularios_filtrados = array_filter($dados_formulario, function($dados) use ($situacao) {
                return $dados->situacao === $situacao;
            });

            // Exibe a tabela apenas se houver formulários para essa categoria
            if (!empty($formularios_filtrados)) {
                echo '<h2>' . $titulo . '</h2>';
                echo '<table class="wp-list-table widefat striped" id="tabela-' . $situacao . '">';
                echo '<thead>';
                echo '<tr>';
                echo '<th class="sort-header">Nome <button class="sort-btn" data-order="asc"><span class="sort-icon">&#9650;</span></button></th>';
                echo '<th class="sort-header">Email <button class="sort-btn sort-by-email" data-order="asc"><span class="sort-icon">&#9650;</span></button></th>';
                echo '<th class="sort-header">Latitude</th>';
                echo '<th class="sort-header">Longitude</th>';
                echo '<th class="sort-header">Serviço</th>';
                echo '<th class="sort-header">Descrição</th>';
                echo '<th class="sort-header">Data e hora <button class="sort-btn sort-by-date" data-order="asc"><span class="sort-icon">&#9650;</span></button></th>';
                echo '<th class="sort-header">Status</th>';
                echo '<th class="sort-header">Ações</th>';
                echo '<th class="sort-header">Excluir</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                // Itera sobre os formulários filtrados
                foreach ($formularios_filtrados as $dados) {
                    echo '<tr>';
                    echo '<td>' . $dados->nome . '</td>';
                    echo '<td>' . $dados->email . '</td>';
                    echo '<td>' . $dados->latitude . '</td>';
                    echo '<td>' . $dados->longitude . '</td>';
                    echo '<td>' . $dados->servico . '</td>';
                    echo '<td>' . $dados->descricao . '</td>';
                    echo '<td>' . date('d/m/Y H:i:s', strtotime($dados->data_hora)) . '</td>';
                    echo '<td>' . $dados->situacao . '</td>';
                    echo '<td>';

                    // Botões de ação com base na situação do formulário
                    if ($situacao === 'Pendente') {
                        echo '<form method="post" action="">';
                        echo '<input type="hidden" name="id" value="' . $dados->id . '">';
                        echo '<button type="submit" name="action" value="approve">Aprovar</button>';
                        echo '<button type="submit" name="action" value="reprove">Negar</button>';
                        echo '</form>';
                    } elseif ($situacao === 'Aprovado') {
                        echo '<form method="post" action="">';
                        echo '<input type="hidden" name="action" value="reprove">';
                        echo '<input type="hidden" name="id" value="' . $dados->id . '">';
                        echo '<button type="submit">Negar</button>';
                        echo '</form>';
                    } elseif ($situacao === 'Negado') {
                        echo '<form method="post" action="">';
                        echo '<input type="hidden" name="action" value="approve">';
                        echo '<input type="hidden" name="id" value="' . $dados->id . '">';
                        echo '<button type="submit">Aprovar</button>';
                        echo '</form>';
                    }

                    echo '</td>';
                    echo '<td>';
                    echo '<a href="?page=lc_admin&action=delete&id=' . $dados->id . '">Excluir</a>';
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            }
        }
        ?>
    </div>

    <!-- Carregue o jQuery antes de qualquer outro script que o utilize -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Carregue o Leaflet antes de qualquer script que o utilize -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Linkando arquivo javascript -->
    <script src="<?php echo plugin_dir_url(__FILE__); ?>admin_script.js"></script>
</body>
</html>
