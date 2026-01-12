<?php
if (!defined('ABSPATH')) exit;

class Growfox_Exchange_Rate_Tag extends \Elementor\Core\DynamicTags\Tag {
    
    public function get_name() {
        return 'growfox-exchange-rate';
    }
    
    public function get_title() {
        return 'อัตราแลกเปลี่ยน';
    }
    
    public function get_group() {
        return 'growfox';
    }
    
    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY,
        ];
    }
    
    protected function register_controls() {
        // เลือกสกุลเงิน (Dropdown แบบ Dynamic)
        $this->add_control('currency', [
            'label' => 'สกุลเงิน',
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_currency_options(),
            'default' => '',
        ]);
        
        // เลือกประเภทข้อมูล
        $this->add_control('field', [
            'label' => 'ข้อมูลที่ต้องการแสดง',
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'currency_id' => 'รหัสสกุลเงิน (USD, EUR)',
                'currency_name_th' => 'ชื่อสกุลเงิน (ไทย)',
                'currency_name_eng' => 'ชื่อสกุลเงิน (อังกฤษ)',
                'buying_sight' => 'อัตราซื้อตั๋วเงิน',
                'buying_transfer' => 'อัตราซื้อเงินโอน',
                'selling' => 'อัตราขาย',
                'period' => 'วันที่',
            ],
            'default' => 'selling',
        ]);
    }
    
    // ดึงรายการสกุลเงินจาก JSON
    private function get_currency_options() {
        $options = ['' => '-- เลือกสกุลเงิน --'];
        
        // ดึงข้อมูลจาก WordPress Options (ไม่ใช่ ACF)
        $data = get_option('options_exchange_data');
        
        // ถ้ายังไม่มีข้อมูล ให้แสดงสกุลเงินหลักๆ ไว้ก่อน
        if (!$data) {
            return [
                '' => '-- เลือกสกุลเงิน --',
                'USD' => 'USD - ดอลลาร์สหรัฐ',
                'EUR' => 'EUR - ยูโร',
                'GBP' => 'GBP - ปอนด์อังกฤษ',
                'JPY' => 'JPY - เยนญี่ปุ่น',
                'CNY' => 'CNY - หยวนจีน',
                'AUD' => 'AUD - ดอลลาร์ออสเตรเลีย',
                'CAD' => 'CAD - ดอลลาร์แคนาดา',
                'CHF' => 'CHF - ฟรังก์สวิส',
                'HKD' => 'HKD - ดอลลาร์ฮ่องกง',
                'SGD' => 'SGD - ดอลลาร์สิงคโปร์',
                'SEK' => 'SEK - โครนาสวีเดน',
                'NOK' => 'NOK - โครนานอร์เวย์',
                'DKK' => 'DKK - โครนาเดนมาร์ก',
                'NZD' => 'NZD - ดอลลาร์นิวซีแลนด์',
                'KRW' => 'KRW - วอนเกาหลี',
                'MYR' => 'MYR - ริงกิตมาเลเซีย',
                'INR' => 'INR - รูปีอินเดีย',
                'IDR' => 'IDR - รูเปียห์อินโดนีเซีย',
                'PHP' => 'PHP - เปโซฟิลิปปินส์',
                'VND' => 'VND - ดองเวียดนาม',
            ];
        }
        
        $rates = json_decode($data, true);
        if (!$rates || !is_array($rates)) {
            return $options;
        }
        
        foreach ($rates as $rate) {
            $currency_id = $rate['currency_id'] ?? '';
            $currency_name = $rate['currency_name_th'] ?? $rate['currency_name_eng'] ?? '';
            
            if ($currency_id) {
                $options[$currency_id] = $currency_id . ($currency_name ? ' - ' . $currency_name : '');
            }
        }
        
        return $options;
    }
    
    public function render() {
        $settings = $this->get_settings();
        $currency = $settings['currency'];
        $field = $settings['field'];
        
        if (empty($currency)) {
            echo '-- เลือกสกุลเงิน --';
            return;
        }
        
        // ดึงข้อมูลจาก WordPress Options (ไม่ใช่ ACF)
        $data = get_option('options_exchange_data');
        if (!$data) {
            echo '--';
            return;
        }
        
        $rates = json_decode($data, true);
        if (!$rates) {
            echo '--';
            return;
        }
        
        // หาสกุลเงินที่ต้องการ
        $value = null;
        foreach ($rates as $rate) {
            if (strtoupper($rate['currency_id']) === strtoupper($currency)) {
                $value = $rate[$field] ?? null;
                break;
            }
        }
        
        if ($value === null) {
            echo '--';
            return;
        }
        
        // Format ตามประเภทข้อมูล
        if (in_array($field, ['buying_sight', 'buying_transfer', 'selling'])) {
            echo number_format((float)$value, 4);
        } elseif ($field === 'period') {
            echo date('d/m/Y', strtotime($value));
        } else {
            echo esc_html($value);
        }
    }
}
