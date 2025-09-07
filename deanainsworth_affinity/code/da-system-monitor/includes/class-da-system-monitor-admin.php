<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register and display the System Monitor widget in the WP Admin Dashboard

class DA_System_Monitor_Admin {

    public function __construct() {
        add_action( 'wp_dashboard_setup', array( $this, 'register_da_system_monitor_widget' ) );
        add_action( 'admin_post_da_system_monitor_download', array( $this, 'download_da_system_monitor_json' ) );
    }

    public function register_da_system_monitor_widget() {
        if( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        wp_add_dashboard_widget(
            'da_system_monitor_widget',
            __( 'DA System Monitor', 'da-system-monitor' ),
            array( $this, 'render_da_system_monitor_widget' ),
            NULL,
            NULL,
            'normal',
            'high'
        );
    }

    public function render_da_system_monitor_widget() {

        // Get system info
        $info = $this->get_da_system_monitor_info();

        // Output the info

        echo '<div class="da-container">';
        echo '<div class="da-item"><h3><strong>' . __( 'PHP Version:', 'da-system-monitor' ) . '</strong></h3><p>' . esc_html( $info['php_version'] ) . '</p></div>';
        echo '<div class="da-item"><h3><strong>' . __( 'WordPress Version:', 'da-system-monitor' ) . '</strong></h3><p>' . esc_html( $info['wp_version'] ) . '</p></div>';
        echo '<div class="da-item"><h3><strong>' . __( 'Memory Usage:', 'da-system-monitor' ) . '</strong></h3><p>' . esc_html( $info['memory_usage'] ) . ' / ' . esc_html( $info['memory_limit'] ) . '</p></div>';
        echo '<div class="da-item"><h3><strong>' . __( 'Plugin Count:', 'da-system-monitor' ) . '</strong></h3><p>' . esc_html( $info['plugin_count'] ) . '</p></div>';
        echo '</div>';

        // Create a nonce
        $nonce = wp_create_nonce( 'da_system_monitor_download' );

        // Add download button with nonce
        $download_url = add_query_arg( array(
            'action' => 'da_system_monitor_download',
            'nonce'  => $nonce,
        ), admin_url( 'admin-post.php' ) );

        echo '<p><a href="' . esc_url( $download_url ) . '" class="button button-primary">' . __( 'Export System Info', 'da-system-monitor' ) . '</a></p>';
    
    }

    private function get_da_system_monitor_info() {
        return array(
            'php_version'   => phpversion(),
            'wp_version'    => get_bloginfo( 'version' ),
            'memory_usage'  => size_format( memory_get_usage( true ) ),
            'memory_limit'  => ini_get( 'memory_limit' ),
            'plugin_count'  => count( get_plugins() ),
        );
    }

    public function download_da_system_monitor_json() {

        // Check caps
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have permission to perform this action.', 'da-system-monitor' ) );
        }

        // Verify nonce
        if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_GET['nonce'] ), 'da_system_monitor_download' ) ) {
            wp_die( __( 'Security check failed.', 'da-system-monitor' ) );
        }

        $info = $this->get_da_system_monitor_info();

        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="system-info.json"' );
        echo wp_json_encode( $info, JSON_PRETTY_PRINT );
        exit;
    }

}