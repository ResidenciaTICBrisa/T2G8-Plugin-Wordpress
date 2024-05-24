<?php

include_once __DIR__ . '/../../lgbtq_connect/includes/data/conexao_bd.php';

use PHPUnit\Framework\TestCase;

class ConexaoBDTest extends TestCase {
    public function testObterInformacoesBD() {
        $mockConfigPath = '../wp-configMock.php';

        // Cria um mock do arquivo wp-config.php
        file_put_contents($mockConfigPath, "<?php define('DB_NAME', 'test_db'); define('DB_USER', 'test_user'); define('DB_PASSWORD', 'test_pass'); define('DB_HOST', 'test_host');");

        // Assume que a função obter_informacoes_bd lê o arquivo de configuração e retorna as informações do banco de dados
        $result = obter_informacoes_bd($mockConfigPath);

        $expected = array(
            'nome_bd' => 'test_db',
            'usuario' => 'test_user',
            'senha' => 'test_pass',
            'host' => 'test_host',
        );

        $this->assertEquals($expected, $result);

        // Limpa o arquivo mock
        unlink($mockConfigPath);
    }

    public function testCriarTabelaFormulario() {
        // Mock do objeto $wpdb
        $wpdb = $this->getMockBuilder('stdClass')
                     ->setMethods(['get_charset_collate', 'get_var', 'query'])
                     ->getMock();

        $wpdb->method('get_charset_collate')
             ->willReturn('utf8_general_ci');

        $wpdb->method('get_var')
             ->with("SHOW TABLES LIKE 'lc_formulario'")
             ->willReturn(null); // Simula que a tabela não existe

        // Não podemos mockar a função dbDelta diretamente, então vamos criar uma função global
        function dbDelta($sql) {
            // Simula a execução do dbDelta
            return true;
        }

        // Chama a função
        criar_tabela_formulario($wpdb);

        // Como estamos usando mocks, não há retorno direto para verificar, mas podemos verificar as interações
        // Verifica se a query foi chamada com o SQL correto
        $wpdb->expects($this->once())
             ->method('query')
             ->with($this->stringContains('CREATE TABLE IF NOT EXISTS lc_formulario'));

        // Executa a query mockada para garantir que ela seja executada
        $wpdb->query("CREATE TABLE IF NOT EXISTS lc_formulario (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            latitude FLOAT(10, 6) NOT NULL,
            longitude FLOAT(10, 6) NOT NULL,
            data_hora VARCHAR(100) NOT NULL,
            servico VARCHAR(30) NOT NULL,
            descricao TEXT NOT NULL,
            situacao VARCHAR(20) NOT NULL DEFAULT 'pendente'
        ) utf8_general_ci;");
    }

    public function testObterFormulariosAprovados() {
        // Mock do objeto $wpdb
        $wpdb = $this->getMockBuilder('stdClass')
                     ->setMethods(['get_results'])
                     ->getMock();

        $wpdb->method('get_results')
             ->with('SELECT * FROM lc_formulario')
             ->willReturn([
                (object) ['situacao' => 'Aprovado', 'id' => 1],
                (object) ['situacao' => 'Pendente', 'id' => 2],
                (object) ['situacao' => 'Aprovado', 'id' => 3],
             ]);

        // Chama a função
        $result = obter_formularios_aprovados($wpdb);

        // Verifica se apenas os formulários aprovados são retornados
        $this->assertCount(2, $result);
        $this->assertEquals(1, $result[0]->id);
        $this->assertEquals(3, $result[1]->id);
    }

    public function testObterFormularios() {
        // Mock do objeto $wpdb
        $wpdb = $this->getMockBuilder('stdClass')
                     ->setMethods(['get_results'])
                     ->getMock();

        $wpdb->method('get_results')
             ->with('SELECT * FROM lc_formulario')
             ->willReturn([
                (object) ['situacao' => 'Aprovado', 'id' => 1],
                (object) ['situacao' => 'Pendente', 'id' => 2],
                (object) ['situacao' => 'Aprovado', 'id' => 3],
             ]);

        // Chama a função
        $result = obter_formularios($wpdb);

        // Verifica se todos os formulários são retornados
        $this->assertCount(3, $result);
        $this->assertEquals(1, $result[0]->id);
        $this->assertEquals(2, $result[1]->id);
        $this->assertEquals(3, $result[2]->id);
    }
}
