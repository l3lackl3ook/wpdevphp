<?php
/**
 * Elementor Integration
 * ลงทะเบียน Dynamic Tags
 */

if (!defined('ABSPATH')) {
    exit;
}

class TFD_Elementor_Integration {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('elementor/dynamic_tags/register', array($this, 'register_dynamic_tags'));
        add_action('elementor/dynamic_tags/register_tags', array($this, 'register_tags'));
    }
    
    public function register_dynamic_tags($dynamic_tags_manager) {
        // ลงทะเบียนกลุ่ม Thailand Financial
        $dynamic_tags_manager->register_group('thailand-financial', [
            'title' => 'Thailand Financial'
        ]);
    }
    
    public function register_tags($dynamic_tags_manager) {
        // ลงทะเบียน Dynamic Tags
        require_once TFD_PLUGIN_DIR . 'includes/dynamic-tags/exchange-rate-tag.php';
        require_once TFD_PLUGIN_DIR . 'includes/dynamic-tags/oil-price-tag.php';
        require_once TFD_PLUGIN_DIR . 'includes/dynamic-tags/gold-price-tag.php';
        
        $dynamic_tags_manager->register(new TFD_Exchange_Rate_Tag());
        $dynamic_tags_manager->register(new TFD_Oil_Price_Tag());
        $dynamic_tags_manager->register(new TFD_Gold_Price_Tag());
    }
}
