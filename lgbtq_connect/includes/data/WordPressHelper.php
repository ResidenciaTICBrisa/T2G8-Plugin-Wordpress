<?php

class WordPressHelper {
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

    public static function insert_data_into_db($data) {
        global $wpdb;
        $table_name = "lc_formulario";
        return $wpdb->insert($table_name, $data);
    }

    public static function get_current_time() {
        return current_time('mysql');
    }

    public static function get_admin_email() {
        return get_option('admin_email');
    }

    public static function get_admin_panel_url() {
        return admin_url('admin.php?page=lc_admin');
    }

    public static function send_email($to, $subject, $message) {
        return wp_mail($to, $subject, $message);
    }
}
?>
