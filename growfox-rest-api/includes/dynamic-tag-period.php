<?php
if (!defined('ABSPATH')) exit;

class Growfox_Period_Tag extends \Elementor\Core\DynamicTags\Tag {
    
    public function get_name() {
        return 'growfox-period';
    }
    
    public function get_title() {
        return 'วันที่อัตราแลกเปลี่ยน';
    }
    
    public function get_group() {
        return 'growfox';
    }
    
    public function get_categories() {
        return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY];
    }
    
    protected function register_controls() {
        $this->add_control('format', [
            'label' => 'รูปแบบวันที่',
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'thai' => 'แบบไทย (01/01/2568)',
                'eng' => 'แบบอังกฤษ (01/01/2025)',
                'full_thai' => 'เต็ม (1 มกราคม 2568)',
                'full_eng' => 'Full (January 1, 2025)',
            ],
            'default' => 'thai',
        ]);
    }
    
    public function render() {
        $settings = $this->get_settings();
        $format = $settings['format'];
        
        // ดึงข้อมูลจาก WordPress Options
        $data = get_option('options_exchange_data');
        if (!$data) {
            echo '--';
            return;
        }
        
        $rates = json_decode($data, true);
        if (!$rates || !is_array($rates) || empty($rates)) {
            echo '--';
            return;
        }
        
        // ใช้วันที่จากสกุลเงินแรก (ทุกสกุลมีวันที่เดียวกัน)
        $period = $rates[0]['period'] ?? null;
        
        if (!$period) {
            echo '--';
            return;
        }
        
        // แปลงวันที่
        $timestamp = strtotime($period);
        
        switch ($format) {
            case 'thai':
                $year = date('Y', $timestamp) + 543;
                echo date('d/m/', $timestamp) . $year;
                break;
                
            case 'eng':
                echo date('d/m/Y', $timestamp);
                break;
                
            case 'full_thai':
                $months_th = [
                    1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม',
                    4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
                    7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน',
                    10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                ];
                $day = date('j', $timestamp);
                $month = $months_th[(int)date('n', $timestamp)];
                $year = date('Y', $timestamp) + 543;
                echo "$day $month $year";
                break;
                
            case 'full_eng':
                echo date('F j, Y', $timestamp);
                break;
                
            default:
                echo date('d/m/Y', $timestamp);
        }
    }
}
