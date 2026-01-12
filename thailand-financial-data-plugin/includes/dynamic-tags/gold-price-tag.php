<?php
/**
 * Gold Price Dynamic Tag
 * แสดงราคาทอง
 */

if (!defined('ABSPATH')) {
    exit;
}

class TFD_Gold_Price_Tag extends \Elementor\Core\DynamicTags\Tag {
    
    public function get_name() {
        return 'tfd-gold-price';
    }
    
    public function get_title() {
        return 'ราคาทอง';
    }
    
    public function get_group() {
        return 'thailand-financial';
    }
    
    public function get_categories() {
        return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY];
    }
    
    protected function register_controls() {
        // เลือกประเภททอง
        $this->add_control(
            'gold_type',
            [
                'label' => 'ประเภททอง',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'bar_buy' => 'ทองคำแท่ง - รับซื้อ',
                    'bar_sell' => 'ทองคำแท่ง - ขายออก',
                    'jewelry_buy' => 'ทองรูปพรรณ - รับซื้อ',
                    'jewelry_sell' => 'ทองรูปพรรณ - ขายออก',
                    'change' => 'เปลี่ยนแปลง',
                    'date' => 'วันที่',
                    'time' => 'เวลา',
                ],
                'default' => 'bar_buy',
            ]
        );
        
        // ข้อความเมื่อไม่มีข้อมูล
        $this->add_control(
            'fallback',
            [
                'label' => 'ข้อความเมื่อไม่มีข้อมูล',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '-',
            ]
        );
    }
    
    public function render() {
        $settings = $this->get_settings();
        $gold_type = $settings['gold_type'];
        $fallback = $settings['fallback'];
        
        $gold_prices = get_option('tfd_gold_prices', array());
        
        if (isset($gold_prices[$gold_type])) {
            $value = $gold_prices[$gold_type];
            
            // ถ้าเป็นราคา (ไม่ใช่วันที่/เวลา) ให้เช็คค่าเปลี่ยนแปลง
            if (in_array($gold_type, ['bar_buy', 'bar_sell', 'jewelry_buy', 'jewelry_sell'])) {
                $change = isset($gold_prices['change']) ? $gold_prices['change'] : '';
                $color = (strpos($change, '-') !== false) ? '#ff0000' : '#00ff00';
                echo '<span style="color: ' . esc_attr($color) . ';">' . esc_html($value) . '</span>';
            } else {
                echo esc_html($value);
            }
        } else {
            echo esc_html($fallback);
        }
    }
}
