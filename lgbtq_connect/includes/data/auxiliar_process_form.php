<?php
class Auxiliar_Process_Forms {
    // Função para sanitizar os dados
    public static function sanitize_data($data, $type = 'text') {
        switch ($type) {
            case 'email':
                return filter_var($data, FILTER_SANITIZE_EMAIL);
            case 'textarea':
                return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            case 'float':
                return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            default:
                return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
    }
    // Função para inserir dados no banco de dados
    public static function insert_data_into_db($wpdb, $data) {
        $table_name = "lc_formulario";
        return $wpdb->insert($table_name, $data);
    }
    // Função para obter o horário atual
    public static function get_current_time() {
        return current_time('mysql');
    }
    // Função para obter o e-mail do administrador
    public static function get_admin_email() {
        return get_option('admin_email');
    }
    // Função para obter a URL do painel de administração
    public static function get_admin_panel_url() {
        return admin_url('admin.php?page=lc_admin');
    }
    // Função para enviar e-mails
    public static function send_email($to, $subject, $message) {
        return wp_mail($to, $subject, $message);
    }
}
?>
