<?php
use PHPUnit\Framework\TestCase;

include_once __DIR__ . '/../../lgbtq_connect/includes/admin/formulario-admin-page.php';

class AlteraStatusTest extends TestCase {
    private $wpdb;

    protected function setUp(): void {
        // Mock do objeto $wpdb
        $this->wpdb = $this->getMockBuilder('wpdb')
                           ->setMethods(['prepare', 'query'])
                           ->getMock();
    }

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
