<?php
/**
 * Plugin Name: Growfox Rest API
 * Description: รับข้อมูลอัตราแลกเปลี่ยนจาก Make.com (รองรับ 48 สกุลเงิน)
 * Version: 2.7.0
 * Author: Growfox
 */

if (!defined('ABSPATH')) exit;

define('GROWFOX_VERSION', '2.7.0');
define('GROWFOX_PATH', plugin_dir_path(__FILE__));

class Growfox_Rest_API {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
        
        if (class_exists('ACF')) {
            require_once GROWFOX_PATH . 'includes/acf-fields.php';
            require_once GROWFOX_PATH . 'includes/hide-acf-tags.php';
        }
        
        if (did_action('elementor/loaded')) {
            require_once GROWFOX_PATH . 'includes/elementor-dynamic-tags.php';
            require_once GROWFOX_PATH . 'includes/dynamic-tag-period.php';
            require_once GROWFOX_PATH . 'includes/elementor-widget-exchange-table.php';
            add_action('elementor/widgets/register', [$this, 'register_widgets']);
        }
    }
    
    public function register_routes() {
        // Endpoint: รับข้อมูลทั้งหมด (Array)
        register_rest_route('growfox/v1', '/exchange-rates', [
            [
                'methods' => 'POST',
                'callback' => [$this, 'update_all_rates'],
                'permission_callback' => '__return_true'
            ],
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_all_rates'],
                'permission_callback' => '__return_true'
            ]
        ]);
        
        // Endpoint: รับข้อมูลทีละสกุล
        register_rest_route('growfox/v1', '/exchange-rate', [
            'methods' => 'POST',
            'callback' => [$this, 'update_single_rate'],
            'permission_callback' => '__return_true'
        ]);
    }
    
    // GET: ดูข้อมูลทั้งหมด (สำหรับ debug)
    public function get_all_rates($request) {
        $data = get_option('options_exchange_data');
        $period = get_option('options_period');
        $last_update = get_option('options_last_update');
        
        if (!$data) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'No data available',
                'last_update' => $last_update
            ], 200);
        }
        
        $rates = json_decode($data, true);
        
        return new WP_REST_Response([
            'success' => true,
            'count' => count($rates),
            'period' => $period,
            'last_update' => $last_update,
            'data' => $rates
        ], 200);
    }
    
    // รับข้อมูลทั้งหมด (48 สกุล)
    public function update_all_rates($request) {
        // ลองอ่านข้อมูลหลายวิธี
        $raw_body = $request->get_body();
        error_log('Growfox API - Raw body: ' . substr($raw_body, 0, 500));
        error_log('Growfox API - Content-Type: ' . $request->get_content_type());
        
        // ลอง decode JSON
        $data = json_decode($raw_body, true);
        
        // ถ้า decode ไม่ได้ ลองใช้ get_json_params
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Growfox API - JSON decode error: ' . json_last_error_msg());
            $data = $request->get_json_params();
            
            // ถ้ายังไม่ได้ ลองใช้ get_params
            if (empty($data)) {
                $data = $request->get_params();
            }
        }
        
        error_log('Growfox API - Data type: ' . gettype($data));
        
        if (empty($data)) {
            error_log('Growfox API - Empty data received');
            return new WP_Error('invalid_data', 'Invalid data format. Raw body: ' . substr($raw_body, 0, 200), ['status' => 400]);
        }
        
        // ตรวจสอบโครงสร้างข้อมูล
        $rates = null;
        
        // ลองหาข้อมูลจาก key ต่างๆ
        if (isset($data['responseContent'])) {
            $rates = $data['responseContent'];
            error_log('Growfox API - Found data in responseContent');
        } elseif (isset($data['data']['responseContent'])) {
            $rates = $data['data']['responseContent'];
            error_log('Growfox API - Found data in data.responseContent');
        } elseif (isset($data['data'])) {
            $rates = $data['data'];
            error_log('Growfox API - Found data in data');
        } elseif (is_array($data)) {
            // ตรวจสอบว่า array มี currency_id หรือไม่
            $first_item = reset($data);
            if (is_array($first_item) && isset($first_item['currency_id'])) {
                $rates = $data;
                error_log('Growfox API - Using data as rates array directly');
            } else {
                // ลองใช้ข้อมูลทั้งหมดเป็น rates
                $rates = $data;
                error_log('Growfox API - Using entire data array');
            }
        }
        
        if (!$rates) {
            error_log('Growfox API - Could not find rates in data structure');
            return new WP_Error('invalid_data', 'Data structure not recognized', ['status' => 400]);
        }
        
        // แปลง Object เป็น Array ถ้าจำเป็น
        if (is_object($rates)) {
            $rates = (array) $rates;
        }
        
        if (!is_array($rates)) {
            error_log('Growfox API - Rates is not an array: ' . gettype($rates));
            return new WP_Error('invalid_data', 'Data must be array or object', ['status' => 400]);
        }
        
        // แปลง associative array ที่มี key เป็นตัวเลขเป็น indexed array
        $rates = array_values($rates);
        
        // แปลงแต่ละ item เป็น array ถ้าเป็น object
        $rates = array_map(function($item) {
            return is_object($item) ? (array) $item : $item;
        }, $rates);
        
        error_log('Growfox API - Processing ' . count($rates) . ' rates');
        
        // เก็บเป็น JSON ใน WordPress Options (ไม่ใช่ ACF)
        $json_data = json_encode($rates, JSON_UNESCAPED_UNICODE);
        update_option('options_exchange_data', $json_data);
        
        // เก็บวันที่
        if (isset($rates[0]['period'])) {
            update_option('options_period', $rates[0]['period']);
        }
        
        update_option('options_last_update', current_time('mysql'));
        
        error_log('Growfox API - Success: Updated ' . count($rates) . ' currencies');
        
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Updated ' . count($rates) . ' currencies',
            'count' => count($rates),
            'timestamp' => current_time('mysql')
        ], 200);
    }
    
    // รับข้อมูลทีละสกุล
    public function update_single_rate($request) {
        $data = $request->get_json_params();
        
        if (empty($data['currency_id'])) {
            return new WP_Error('missing_currency', 'Currency ID required', ['status' => 400]);
        }
        
        // ดึงข้อมูลเดิมจาก WordPress Options
        $existing = get_option('options_exchange_data');
        $rates = $existing ? json_decode($existing, true) : [];
        
        // อัปเดตหรือเพิ่มใหม่
        $found = false;
        foreach ($rates as &$rate) {
            if ($rate['currency_id'] === $data['currency_id']) {
                $rate = array_merge($rate, $data);
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $rates[] = $data;
        }
        
        // บันทึกลง WordPress Options (ไม่ใช่ ACF)
        $json_data = json_encode($rates, JSON_UNESCAPED_UNICODE);
        update_option('options_exchange_data', $json_data);
        update_option('options_last_update', current_time('mysql'));
        
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Updated ' . $data['currency_id']
        ], 200);
    }
    
    // ลงทะเบียน Elementor Widgets
    public function register_widgets($widgets_manager) {
        $widgets_manager->register(new \Growfox_Exchange_Table_Widget());
    }
}

function growfox_rest_api() {
    return Growfox_Rest_API::instance();
}
growfox_rest_api();
