<?php
use PHPUnit\Framework\TestCase;

include_once __DIR__ . '/../../lgbtq_connect/includes/admin/formulario-admin-page.php';

// Defina uma classe `wpdb` fictícia se ela não existir
if (!class_exists('wpdb')) {
    class wpdb {
        public function prepare($query, ...$args) {
            return vsprintf(str_replace(['%s', '%d'], ['\'%s\'', '%d'], $query), $args);
        }
        public function query($query) {
            return true;
        }
        public function update($table, $data, $where) {
            return true;
        }
    }
}

// Funções fictícias de sanitização
if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str) {
        return $str;
    }
}
if (!function_exists('sanitize_email')) {
    function sanitize_email($email) {
        return $email;
    }
}
if (!function_exists('sanitize_textarea_field')) {
    function sanitize_textarea_field($str) {
        return $str;
    }
}

// Função wp_die customizada para testes
class WPDieException extends Exception {}

function wp_die($message) {
    throw new WPDieException($message);
}

class AlteraStatusTest extends TestCase {
    private $wpdb;

    protected function setUp(): void {
        // Mock do objeto $wpdb
        $this->wpdb = $this->getMockBuilder('wpdb')
                           ->onlyMethods(['prepare', 'query', 'update'])
                           ->getMock();
    }

    protected function tearDown(): void {
        unset($_SERVER['REQUEST_METHOD']);
        $_POST = [];
        parent::tearDown();
    }

    // Testes para a função atualizar_formulario
    public function test_atualizar_formulario_success() {
        // Simula um ambiente HTTP com método POST
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Mock de $_POST
        $_POST = [
            'id' => 1,
            'nome' => 'Teste Nome',
            'email' => 'teste@example.com',
            'servico' => 'Teste Serviço',
            'descricao' => 'Teste Descrição',
            'latitude' => '12.345678',
            'longitude' => '98.7654321'
        ];

        // Mock da função conseguir_rua_e_cidade
        $mock_conseguir_rua_e_cidade = function($latitude, $longitude) {
            return ['Mock Rua', 'Mock Cidade'];
        };

        // Defina o que o mock do $wpdb->update deve retornar
        $this->wpdb->expects($this->once())
                   ->method('update')
                   ->with(
                       $this->equalTo('lc_formulario'),
                       $this->equalTo([
                           'nome' => 'Teste Nome',
                           'email' => 'teste@example.com',
                           'servico' => 'Teste Serviço',
                           'descricao' => 'Teste Descrição',
                           'latitude' => '12.345678',
                           'longitude' => '98.7654321',
                           'rua' => 'Mock Rua',
                           'cidade' => 'Mock Cidade'
                       ]),
                       $this->equalTo(['id' => 1])
                   )
                   ->willReturn(1);
        
        // Defina global $wpdb
        global $wpdb;
        $wpdb = $this->wpdb;

        // Chame a função atualizar_formulario
        atualizar_formulario($this->wpdb, $mock_conseguir_rua_e_cidade);
    }

    public function test_atualizar_formulario_missing_data() {
        $this->expectException(WPDieException::class);
        
        // Simule a chamada para `atualizar_formulario` sem passar os dados obrigatórios
        $_POST = array(); // Dados insuficientes no POST
        
        // Defina global $wpdb
        global $wpdb;
        $wpdb = $this->wpdb;

        // Defina a função conseguir_rua_e_cidade como uma função fictícia
        $mock_conseguir_rua_e_cidade = function($latitude, $longitude) {
            return ['Mock Rua', 'Mock Cidade'];
        };

        atualizar_formulario($this->wpdb, $mock_conseguir_rua_e_cidade);
    }
    

    // Testes para a função alteraStatus
    public function testAlteraStatusReturnsFalseWhenWpdbNotSet() {
        $result = alteraStatus(null, 1, 'new_status');
        $this->assertFalse($result);
    }

    public function testAlteraStatusReturnsFalseWhenIdIsEmpty() {
        $result = alteraStatus($this->wpdb, '', 'new_status');
        $this->assertFalse($result);
    }

    public function testAlteraStatusReturnsFalseWhenNewStatusIsEmpty() {
        $result = alteraStatus($this->wpdb, 1, '');
        $this->assertFalse($result);
    }

    public function testAlteraStatusReturnsFalseWhenQueryFails() {
        // Configura o mock para a função prepare
        $this->wpdb->expects($this->once())
                   ->method('prepare')
                   ->with("UPDATE lc_formulario SET situacao = %s WHERE id = %d", 'new_status', 1)
                   ->willReturn('prepared_query');

        // Configura o mock para a função query
        $this->wpdb->expects($this->once())
                   ->method('query')
                   ->with('prepared_query')
                   ->willReturn(false);

        $result = alteraStatus($this->wpdb, 1, 'new_status');
        $this->assertFalse($result);
    }

    public function testAlteraStatusReturnsTrueWhenQuerySucceeds() {
        // Configura o mock para a função prepare
        $this->wpdb->expects($this->once())
                   ->method('prepare')
                   ->with("UPDATE lc_formulario SET situacao = %s WHERE id = %d", 'new_status', 1)
                   ->willReturn('prepared_query');

        // Configura o mock para a função query
        $this->wpdb->expects($this->once())
                   ->method('query')
                   ->with('prepared_query')
                   ->willReturn(true);

        $result = alteraStatus($this->wpdb, 1, 'new_status');
        $this->assertTrue($result);
    }
}
?>
