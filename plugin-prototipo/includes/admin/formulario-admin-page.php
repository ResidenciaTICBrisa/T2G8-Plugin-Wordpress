<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php global $dados_formulario; ?>
                <?php foreach ($dados_formulario as $dados): ?>
                    <tr>
                        <td><?php echo $dados->nome; ?></td>
                        <td><?php echo $dados->email; ?></td>
                        <td><?php echo $dados->latitude; ?></td>
                        <td><?php echo $dados->longitude; ?></td>
                        <td><?php echo $dados->descricao; ?></td>
                        <td><a href="?page=meus_dados&action=delete&id=<?php echo $dados->id; ?>">Excluir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    
</body>
</html>