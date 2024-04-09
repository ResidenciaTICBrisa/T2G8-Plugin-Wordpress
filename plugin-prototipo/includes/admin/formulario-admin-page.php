<!DOCTYPE html>
<head>
</head>
<body>
    <div class="wrap">
        <h2>Dados do Formulário</h2>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Serviço</th>
                    <th>Descrição</th>
                    <th>Data e hora</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Consulta os dados da tabela formulario
            global $wpdb;
            $dados_formulario = $wpdb->get_results("SELECT * FROM lc_formulario");
            foreach ($dados_formulario as $dados) {
                echo '<tr>';
                echo '<td>' . $dados->nome . '</td>';
                echo '<td>' . $dados->email . '</td>';
                echo '<td>' . $dados->latitude . '</td>';
                echo '<td>' . $dados->longitude . '</td>';
                echo '<td>' . $dados->servico . '</td>';
                echo '<td>' . $dados->descricao . '</td>';
                echo '<td>' . $dados->data_hora . '</td>';
                echo '<td>' . $dados->situacao . '</td>';
                echo '<td><a href="?page=lc_admin&action=delete&id=' . $dados->id . '">Excluir</a></td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>