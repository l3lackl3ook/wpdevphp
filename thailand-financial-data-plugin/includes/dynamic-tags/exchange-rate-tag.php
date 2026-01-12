<?php
/**
 * Exchange Rate Dynamic Tag
 * แสดงอัตราแลกเปลี่ยน
 */

if (!defined('ABSPATH')) {
    exit;
}

class TFD_Exchange_Rate_Tag extends \Elementor\Core\DynamicTags\Tag {
    
    public function get_name() {
        return 'tfd-exchange-rate';
    }
    
    public function get_title() {
        return 'อัตราแลกเปลี่ยน';
    }
    
    public function get_group() {
        return 'thailand-financial';
    }
    
    public function get_categories() {
        return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY];
    }
    
    protected function register_controls() {
        // เลือกสกุลเงิน
        $this->add_control(
            'currency',
            [
                'label' => 'สกุลเงิน',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'USD' => 'USD - ดอลลาร์สหรัฐ',
                    'EUR' => 'EUR - ยูโร',
                    'GBP' => 'GBP - ปอนด์',
                    'JPY' => 'JPY - เยน',
                    'HKD' => 'HKD - ดอลลาร์ฮ่องกง',
                    'MYR' => 'MYR - ริงกิต',
                    'SGD' => 'SGD - ดอลลาร์สิงคโปร์',
                    'BND' => 'BND - ดอลลาร์บรูไน',
                    'PHP' => 'PHP - เปโซ',
                    'IDR' => 'IDR - รูเปียห์',
                    'INR' => 'INR - รูปี',
                    'CHF' => 'CHF - ฟรังก์สวิส',
                    'AUD' => 'AUD - ดอลลาร์ออสเตรเลีย',
                    'NZD' => 'NZD - ดอลลาร์นิวซีแลนด์',
                    'CAD' => 'CAD - ดอลลาร์แคนาดา',
                    'SEK' => 'SEK - โครนาสวีเดน',
                    'DKK' => 'DKK - โครนาเดนมาร์ก',
                    'NOK' => 'NOK - โครนานอร์เวย์',
                    'CNY' => 'CNY - หยวน',
                    'MXN' => 'MXN - เปโซเม็กซิโก',
                    'ZAR' => 'ZAR - แรนด์',
                    'KRW' => 'KRW - วอน',
                    'TWD' => 'TWD - ดอลลาร์ไต้หวัน',
                    'KWD' => 'KWD - ดีนาร์คูเวต',
                    'SAR' => 'SAR - ริยาล',
                    'AED' => 'AED - เดอร์แฮม',
                    'MMK' => 'MMK - จ๊าต',
                    'BDT' => 'BDT - ตากา',
                    'CZK' => 'CZK - โครนาเช็ก',
                    'KHR' => 'KHR - เรียล',
                    'KES' => 'KES - ชิลลิงเคนยา',
                    'LAK' => 'LAK - กีบ',
                    'RUB' => 'RUB - รูเบิล',
                    'VND' => 'VND - ด่อง',
                    'EGP' => 'EGP - ปอนด์อียิปต์',
                    'PLN' => 'PLN - ซลอตี',
                    'LKR' => 'LKR - รูปีศรีลังกา',
                    'IQD' => 'IQD - ดีนาร์อิรัก',
                    'BHD' => 'BHD - ดีนาร์บาห์เรน',
                    'OMR' => 'OMR - ริยาลโอมาน',
                    'JOD' => 'JOD - ดีนาร์จอร์แดน',
                    'QAR' => 'QAR - ริยาลกาตาร์',
                    'MVR' => 'MVR - รูฟิยามัลดีฟส์',
                    'NPR' => 'NPR - รูปีเนปาล',
                    'PGK' => 'PGK - กีนาปาปัวนิวกินี',
                    'ILS' => 'ILS - เชเกลอิสราเอล',
                    'HUF' => 'HUF - ฟอรินต์ฮังการี',
                    'PKR' => 'PKR - รูปีปากีสถาน',
                ],
                'default' => 'USD',
            ]
        );
        
        // เลือกประเภทข้อมูล
        $this->add_control(
            'field',
            [
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
        $currency = $settings['currency'];
        $field = $settings['field'];
        $fallback = $settings['fallback'];
        
        if (empty($currency)) {
            echo esc_html($fallback);
            return;
        }
        
        // ดึงข้อมูลจาก Thailand Financial (แบบ Growfox)
        $json_data = get_option('tfd_exchange_rates_json', '');
        
        if (empty($json_data)) {
            echo esc_html($fallback);
            return;
        }
        
        $rates = json_decode($json_data, true);
        if (!$rates || !is_array($rates)) {
            echo esc_html($fallback);
            return;
        }
        
        // หาสกุลเงินที่ต้องการ
        $value = null;
        foreach ($rates as $rate) {
            if (strtoupper($rate['currency_id'] ?? '') === strtoupper($currency)) {
                $value = $rate[$field] ?? null;
                break;
            }
        }
        
        if ($value === null || $value === '' || $value === '-') {
            echo esc_html($fallback);
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
