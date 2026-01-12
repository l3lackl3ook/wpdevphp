<?php
/**
 * Oil Price Dynamic Tag
 * แสดงราคาน้ำมัน
 */

if (!defined('ABSPATH')) {
    exit;
}

class TFD_Oil_Price_Tag extends \Elementor\Core\DynamicTags\Tag {
    
    public function get_name() {
        return 'tfd-oil-price';
    }
    
    public function get_title() {
        return 'ราคาน้ำมัน';
    }
    
    public function get_group() {
        return 'thailand-financial';
    }
    
    public function get_categories() {
        return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY];
    }
    
    protected function register_controls() {
        // เลือกชนิดน้ำมัน
        $this->add_control(
            'oil_type',
            [
                'label' => 'ชนิดน้ำมัน',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'diesel_premium' => 'ไฮพรีเมียมดีเซล S',
                    'biodiesel' => 'ไฮดีเซล S (ไบโอดีเซล)',
                    'gasohol_97' => 'ไฮพรีเมียม 97 แก๊สโซฮอล์ 95',
                    'e85' => 'แก๊สโซฮอล์ E85 S EVO',
                    'e20' => 'แก๊สโซฮอล์ E20 S EVO',
                    'gasohol_91' => 'แก๊สโซฮอล์ 91 S EVO',
                    'gasohol_95' => 'แก๊สโซฮอล์ 95 S EVO',
                ],
                'default' => 'gasohol_91',
            ]
        );
        
        // เลือกวัน
        $this->add_control(
            'day',
            [
                'label' => 'วัน',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'today' => 'วันนี้',
                    'tomorrow' => 'พรุ่งนี้',
                ],
                'default' => 'today',
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
        $oil_type = $settings['oil_type'];
        $day = $settings['day'];
        $fallback = $settings['fallback'];
        
        $oil_prices = get_option('tfd_oil_prices', array());
        
        if (isset($oil_prices[$oil_type][$day])) {
            echo esc_html($oil_prices[$oil_type][$day]);
        } else {
            echo esc_html($fallback);
        }
    }
}
