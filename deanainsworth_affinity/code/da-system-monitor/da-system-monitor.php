<?php
/**
 * Plugin Name: DA System Monitor
 * Description: Provides an admin dashboard widget showing system information.
 * Version: 1.0.0
 * Author: Dean Ainsworth
 * Text Domain: da-system-monitor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

final class DA_System_Monitor {

    // Instance of the plugin.

    private static $instance = null;

    // Plugin version.

    public $version = '1.0.0';

    // Admin class instance

    private $admin;

    // Singleton

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Constructor

    private function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        $this->init_classes();
    }

    // Constants

    private function define_constants() {
        define( 'DA_SYSTEM_MONITOR_VERSION', $this->version );
        define( 'DA_SYSTEM_MONITOR_DIR', plugin_dir_path( __FILE__ ) );
        define( 'DA_SYSTEM_MONITOR_URL', plugin_dir_url( __FILE__ ) );
    }

    // Includes

    private function includes() {
        require_once DA_SYSTEM_MONITOR_DIR . 'includes/class-da-system-monitor-admin.php';
    }

    // Hooks

    private function init_hooks() {

        // Activation and Deactivation hooks
        register_activation_hook( __FILE__, array( 'DA_System_Monitor', 'activate' ) );
        register_deactivation_hook( __FILE__, array( 'DA_System_Monitor', 'deactivate' ) );

        // Enqueue admin styles
        add_action( 'admin_enqueue_scripts', function() {
            wp_enqueue_style( 'da-system-monitor-admin', DA_SYSTEM_MONITOR_URL . 'assets/css/admin.css', array(), DA_SYSTEM_MONITOR_VERSION );
        } );

    }

    // Initialize classes

    private function init_classes() {
        if ( is_admin() ) {
            $this->admin = new DA_System_Monitor_Admin();
        }
    }

    // Plugin Activation 

    public static function activate() {
        add_option( 'da_system_monitor_activated', current_time('timestamp') );
    }

    // Plugin Deactivation

    public static function deactivate() {
        delete_option( 'da_system_monitor_activated' );
    }
}

// Initialize

function DA_System_Monitor() {
    return DA_System_Monitor::instance();
}

DA_System_Monitor();
