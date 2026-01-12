<?php
/**
 * REST API Endpoints
 * รับข้อมูลจาก Make.com
 */

if (!defined('ABSPATH')) {
    exit;
}

class TFD_REST_API {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }
    
    public function register_routes() {
        // อัตราแลกเปลี่ยน
        register_rest_route('thailand-financial/v1', '/exchange-rates', array(
            'methods' => 'POST',
            'callback' => array($this, 'update_exchange_rates'),
            'permission_callback' => array($this, 'check_permission'),
        ));
        
        // ราคาน้ำมัน
        register_rest_route('thailand-financial/v1', '/oil-prices', array(
            'methods' => 'POST',
            'callback' => array($this, 'update_oil_prices'),
            'permission_callback' => array($this, 'check_permission'),
        ));
        
        // ราคาทอง
        register_rest_route('thailand-financial/v1', '/gold-prices', array(
            'methods' => 'POST',
            'callback' => array($this, 'update_gold_prices'),
            'permission_callback' => array($this, 'check_permission'),
        ));
        
        // GET endpoints สำหรับดูข้อมูล
        register_rest_route('thailand-financial/v1', '/exchange-rates', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_exchange_rates'),
            'permission_callback' => '__return_true',
        ));
        
        register_rest_route('thailand-financial/v1', '/oil-prices', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_oil_prices'),
            'permission_callback' => '__return_true',
        ));
        
        register_rest_route('thailand-financial/v1', '/gold-prices', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_gold_prices'),
            'permission_callback' => '__return_true',
        ));
    }
    
    public function check_permission($request) {
        // ตรวจสอบ API Key
        $api_key = $request->get_header('X-API-Key');
        $stored_key = get_option('tfd_api_key', '');
        
        if (empty($stored_key)) {
            return true; // ถ้ายังไม่ได้ตั้ง API Key ให้ผ่านไปก่อน
        }
        
        return $api_key === $stored_key;
    }
    
    // อัตราแลกเปลี่ยน
    public function update_exchange_rates($request) {
        $raw_body = $request->get_body();
        $data = json_decode($raw_body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = $request->get_json_params();
        }
        
        if (empty($data)) {
            return new WP_Error('no_data', 'ไม่มีข้อมูล', array('status' => 400));
        }
        
        // บันทึกข้อมูลเป็น JSON string เหมือน Growfox
        $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
        update_option('tfd_exchange_rates_json', $json_data);
        
        // เก็บวันที่
        if (isset($data[0]['period'])) {
            update_option('tfd_exchange_period', $data[0]['period']);
        }
        
        update_option('tfd_exchange_rates_updated', current_time('mysql'));
        
        return new WP_REST_Response(array(
            'success' => true,
            'message' => 'อัปเดตอัตราแลกเปลี่ยนสำเร็จ',
            'count' => count($data),
            'updated_at' => current_time('mysql')
        ), 200);
    }
    
    public function get_exchange_rates($request) {
        $json_data = get_option('tfd_exchange_rates_json', '');
        $period = get_option('tfd_exchange_period', '');
        $updated = get_option('tfd_exchange_rates_updated', '');
        
        if (!$json_data) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'No data available',
                'updated_at' => $updated
            ), 200);
        }
        
        $data = json_decode($json_data, true);
        
        return new WP_REST_Response(array(
            'success' => true,
            'count' => count($data),
            'period' => $period,
            'last_update' => $updated,
            'data' => $data
        ), 200);
    }
    
    // ราคาน้ำมัน
    public function update_oil_prices($request) {
        $body = $request->get_body();
        
        if (empty($body)) {
            return new WP_Error('no_data', 'ไม่มีข้อมูล', array('status' => 400));
        }
        
        // ถ้าเป็น array จาก Make.com ให้แปลงเป็น object
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error('invalid_json', 'JSON ไม่ถูกต้อง: ' . json_last_error_msg(), array('status' => 400));
        }
        
        // ถ้าเป็น array ของน้ำมัน ให้แปลงเป็น format ที่ต้องการ
        if (isset($data[0]) && is_array($data[0])) {
            $formatted = array();
            foreach ($data as $oil) {
                $name = strtolower($oil['oil_name'] ?? $oil['OilName'] ?? '');
                if (strpos($name, '91') !== false) {
                    $formatted['gasohol_91'] = array('today' => $oil['price_today'] ?? $oil['PriceToday'], 'tomorrow' => $oil['price_tomorrow'] ?? $oil['PriceTomorrow']);
                } elseif (strpos($name, '95') !== false && strpos($name, '97') === false) {
                    $formatted['gasohol_95'] = array('today' => $oil['price_today'] ?? $oil['PriceToday'], 'tomorrow' => $oil['price_tomorrow'] ?? $oil['PriceTomorrow']);
                } elseif (strpos($name, '97') !== false) {
                    $formatted['gasohol_97'] = array('today' => $oil['price_today'] ?? $oil['PriceToday'], 'tomorrow' => $oil['price_tomorrow'] ?? $oil['PriceTomorrow']);
                } elseif (strpos($name, 'e85') !== false) {
                    $formatted['e85'] = array('today' => $oil['price_today'] ?? $oil['PriceToday'], 'tomorrow' => $oil['price_tomorrow'] ?? $oil['PriceTomorrow']);
                } elseif (strpos($name, 'e20') !== false) {
                    $formatted['e20'] = array('today' => $oil['price_today'] ?? $oil['PriceToday'], 'tomorrow' => $oil['price_tomorrow'] ?? $oil['PriceTomorrow']);
                } elseif (strpos($name, 'ไฮพรีเมียมดีเซล') !== false || strpos($name, 'premium') !== false && strpos($name, 'ดีเซล') !== false) {
                    $formatted['biodiesel'] = array('today' => $oil['price_today'] ?? $oil['PriceToday'], 'tomorrow' => $oil['price_tomorrow'] ?? $oil['PriceTomorrow']);
                } elseif (strpos($name, 'ไฮดีเซล') !== false || (strpos($name, 'ดีเซล') !== false && strpos($name, 'ไฮพรีเมียม') === false)) {
                    $formatted['diesel_premium'] = array('today' => $oil['price_today'] ?? $oil['PriceToday'], 'tomorrow' => $oil['price_tomorrow'] ?? $oil['PriceTomorrow']);
                }
            }
            $data = $formatted;
        }
        
        update_option('tfd_oil_prices', $data);
        update_option('tfd_oil_prices_updated', current_time('mysql'));
        
        return new WP_REST_Response(array(
            'success' => true,
            'message' => 'อัปเดตราคาน้ำมันสำเร็จ',
            'data' => $data,
            'updated_at' => current_time('mysql')
        ), 200);
    }
    
    public function get_oil_prices($request) {
        $data = get_option('tfd_oil_prices', array());
        $updated = get_option('tfd_oil_prices_updated', '');
        
        return new WP_REST_Response(array(
            'success' => true,
            'data' => $data,
            'updated_at' => $updated
        ), 200);
    }
    
    // ราคาทอง
    public function update_gold_prices($request) {
        $data = $request->get_json_params();
        
        if (empty($data)) {
            return new WP_Error('no_data', 'ไม่มีข้อมูล', array('status' => 400));
        }
        
        update_option('tfd_gold_prices', $data);
        update_option('tfd_gold_prices_updated', current_time('mysql'));
        
        return new WP_REST_Response(array(
            'success' => true,
            'message' => 'อัปเดตราคาทองสำเร็จ',
            'data' => $data,
            'updated_at' => current_time('mysql')
        ), 200);
    }
    
    public function get_gold_prices($request) {
        $data = get_option('tfd_gold_prices', array());
        $updated = get_option('tfd_gold_prices_updated', '');
        
        return new WP_REST_Response(array(
            'success' => true,
            'data' => $data,
            'updated_at' => $updated
        ), 200);
    }
}
