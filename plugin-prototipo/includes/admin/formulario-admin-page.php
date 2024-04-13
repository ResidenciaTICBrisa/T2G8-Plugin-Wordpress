<?php
// Função para aprovar o formulário
function aprovar_formulario($id) {
    global $wpdb;

    // Atualiza o status do formulário para 'Aprovado' no banco de dados
    $query = $wpdb->prepare("UPDATE lc_formulario SET situacao = 'Aprovado' WHERE id = %d", $id);
    $resultado = $wpdb->query($query);

}

function rejeitar_formulario($id) {
    global $wpdb;

    // Atualiza o status do formulário para 'Aprovado' no banco de dados
    $query = $wpdb->prepare("UPDATE lc_formulario SET situacao = 'Negado' WHERE id = %d", $id);
    $resultado = $wpdb->query($query);

}

// Verifica se o parâmetro "action" foi enviado via GET
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
<body>
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
                echo '<table class="wp-list-table widefat striped">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Nome</th>';
                echo '<th>Email</th>';
                echo '<th>Latitude</th>';
                echo '<th>Longitude</th>';
                echo '<th>Serviço</th>';
                echo '<th>Descrição</th>';
                echo '<th>Data e hora</th>';
                echo '<th>Status</th>';
                echo '<th>Ações</th>';
                echo '<th>Excluir</th>';
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
                    echo '<td>';
                    echo '<span id="descricaoCompleta_' . $dados->id . '" style="display:none;">' . $dados->descricao . '</span>';
                    echo substr($dados->descricao, 0, 10) . '... <button onclick="mostrarDescricaoCompleta(' . $dados->id . ')">Ver mais</button>';
                    echo '</td>';
                    echo '<td>' . $dados->data_hora . '</td>';
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
    <script src="/assets/js/script.js"></script>
</body>
</html>
