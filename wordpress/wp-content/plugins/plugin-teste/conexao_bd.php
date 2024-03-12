<?php
// Configurações do banco de dados
$host = 'db'; // Host do banco de dados (no caso do docker é db o nome do serviço)
$usuario = 'admin'; // Nome de usuário do banco de dados
$senha = 'admin'; // Senha do banco de dados
$banco_dados = 'wordpress'; // Nome do banco de dados

// Estabelece a conexão com o banco de dados
$conexao = mysqli_connect($host, $usuario, $senha, $banco_dados);

// Verifica a conexão
if (!$conexao) {
    die('Erro de conexão com o banco de dados: ' . mysqli_connect_error());
}
?>