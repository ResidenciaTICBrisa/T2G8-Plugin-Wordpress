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
                echo '<td><a href="?page=lc_admin&action=approve&id=' . $dados->id . '">Aprovar</a>
                <a href="?page=lc_admin&action=reprove&id=' . $dados->id . '">Negar</a>
                <a href="?page=lc_admin&action=delete&id=' . $dados->id . '">Excluir</a></td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>