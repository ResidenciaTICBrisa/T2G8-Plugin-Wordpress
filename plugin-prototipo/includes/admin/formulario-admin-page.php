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
if (isset($_GET['action']) && $_GET['action'] === 'approve' && isset($_GET['id'])) {
    aprovar_formulario($_GET['id']);
} else if (isset($_GET['action']) && $_GET['action'] === 'reprove' && isset($_GET['id'])) {
    rejeitar_formulario($_GET['id']);
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
                    echo '<td>' . $dados->descricao . '</td>';
                    echo '<td>' . $dados->data_hora . '</td>';
                    echo '<td>' . $dados->situacao . '</td>';
                    echo '<td>';

                    // Botões de ação com base na situação do formulário
                    if ($situacao === 'Pendente') {
                        echo '<a href="?page=lc_admin&action=approve&id=' . $dados->id . '">Aprovar</a>';  
                        echo ' ou ';
                        echo '<a href="?page=lc_admin&action=reprove&id=' . $dados->id . '">Negar</a>';
                    } elseif ($situacao === 'Aprovado') {
                        echo '<a href="?page=lc_admin&action=reprove&id=' . $dados->id . '">Negar</a>';
                    } elseif ($situacao === 'Negado') {
                        echo '<a href="?page=lc_admin&action=approve&id=' . $dados->id . '">Aprovar</a>';
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
</body>
</html>