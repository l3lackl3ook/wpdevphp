<?php
/**
 * Plugin Name: Thailand Financial Data
 * Plugin URI: https://aommoney.com
 * Description: ดึงข้อมูลอัตราแลกเปลี่ยน, ราคาน้ำมัน, ราคาทอง จาก Make.com และแสดงผ่าน Elementor Dynamic Tags
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://aommoney.com
 * Text Domain: thailand-financial-data
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('TFD_VERSION', '1.0.0');
define('TFD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TFD_PLUGIN_URL', plugin_dir_url(__FILE__));

class Thailand_Financial_Data {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
        $this->load_dependencies();
    }
    
    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    private function load_dependencies() {
        require_once TFD_PLUGIN_DIR . 'includes/rest-api.php';
        require_once TFD_PLUGIN_DIR . 'includes/admin-menu.php';
        require_once TFD_PLUGIN_DIR . 'includes/elementor-integration.php';
        require_once TFD_PLUGIN_DIR . 'includes/shortcodes.php';
    }
    
    public function init() {
        // Initialize components
        TFD_REST_API::get_instance();
        TFD_Admin_Menu::get_instance();
        TFD_Elementor_Integration::get_instance();
        TFD_Shortcodes::get_instance();
        
        // Load text domain
        load_plugin_textdomain('thailand-financial-data', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function activate() {
        // Set default options
        if (!get_option('tfd_exchange_rates')) {
            update_option('tfd_exchange_rates', array());
        }
        if (!get_option('tfd_oil_prices')) {
            update_option('tfd_oil_prices', array());
        }
        if (!get_option('tfd_gold_prices')) {
            update_option('tfd_gold_prices', array());
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
}

// Initialize plugin
function thailand_financial_data() {
    return Thailand_Financial_Data::get_instance();
}

thailand_financial_data();
