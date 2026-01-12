<?php
/**
 * Shortcodes
 * สำหรับแสดงข้อมูลในหน้าเว็บ
 */

if (!defined('ABSPATH')) {
    exit;
}

class TFD_Shortcodes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('tfd_exchange_rate', array($this, 'exchange_rate_shortcode'));
        add_shortcode('tfd_oil_price', array($this, 'oil_price_shortcode'));
        add_shortcode('tfd_gold_price', array($this, 'gold_price_shortcode'));
    }
    
    /**
     * Shortcode: [tfd_exchange_rate currency="USD" type="buy"]
     */
    public function exchange_rate_shortcode($atts) {
        $atts = shortcode_atts(array(
            'currency' => 'USD',
            'type' => 'buy',
            'fallback' => '-',
        ), $atts);
        
        $exchange_rates = get_option('tfd_exchange_rates', array());
        
        if (isset($exchange_rates[$atts['currency']][$atts['type']])) {
            return esc_html($exchange_rates[$atts['currency']][$atts['type']]);
        }
        
        return esc_html($atts['fallback']);
    }
    
    /**
     * Shortcode: [tfd_oil_price type="gasohol_91" day="today"]
     */
    public function oil_price_shortcode($atts) {
        $atts = shortcode_atts(array(
            'type' => 'gasohol_91',
            'day' => 'today',
            'fallback' => '-',
        ), $atts);
        
        $oil_prices = get_option('tfd_oil_prices', array());
        
        if (isset($oil_prices[$atts['type']][$atts['day']])) {
            return esc_html($oil_prices[$atts['type']][$atts['day']]);
        }
        
        return esc_html($atts['fallback']);
    }
    
    /**
     * Shortcode: [tfd_gold_price type="bar_buy"]
     */
    public function gold_price_shortcode($atts) {
        $atts = shortcode_atts(array(
            'type' => 'bar_buy',
            'fallback' => '-',
        ), $atts);
        
        $gold_prices = get_option('tfd_gold_prices', array());
        
        if (isset($gold_prices[$atts['type']])) {
            return esc_html($gold_prices[$atts['type']]);
        }
        
        return esc_html($atts['fallback']);
    }
}
