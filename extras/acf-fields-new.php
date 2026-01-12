<?php
/**
 * ACF Field Groups Configuration
 * สร้าง ACF Field Group ใน Database โดยตรง
 * แสดงใน Post, Page, และ Exchange Rate ทั้งหมด
 */

// ตรวจสอบว่ามี ACF ติดตั้งหรือไม่
if (!function_exists('acf_add_local_field_group')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-warning"><p>⚠️ <strong>ACF ไม่ได้ติดตั้ง</strong> - กรุณาติดตั้ง Advanced Custom Fields</p></div>';
    });
    return;
}

/**
 * สร้าง ACF Field Group แบบ Local (แสดงทุก Post Type)
 */
acf_add_local_field_group(array(
    'key' => 'group_exchange_rates',
    'title' => 'ข้อมูลอัตราแลกเปลี่ยน',
    'fields' => array(
        array(
            'key' => 'field_period',
            'label' => 'วันที่',
            'name' => 'period',
            'type' => 'text',
            'instructions' => 'วันที่ของอัตราแลกเปลี่ยน',
        ),
        array(
            'key' => 'field_currency_id',
            'label' => 'รหัสสกุลเงิน',
            'name' => 'currency_id',
            'type' => 'text',
            'instructions' => 'เช่น USD, EUR, JPY',
        ),
        array(
            'key' => 'field_currency_name_th',
            'label' => 'ชื่อสกุลเงิน (ไทย)',
            'name' => 'currency_name_th',
            'type' => 'text',
        ),
        array(
            'key' => 'field_currency_name_eng',
            'label' => 'ชื่อสกุลเงิน (อังกฤษ)',
            'name' => 'currency_name_eng',
            'type' => 'text',
        ),
        array(
            'key' => 'field_buying_sight',
            'label' => 'อัตราซื้อตั๋วเงิน',
            'name' => 'buying_sight',
            'type' => 'text',
        ),
        array(
            'key' => 'field_buying_transfer',
            'label' => 'อัตราซื้อเงินโอน',
            'name' => 'buying_transfer',
            'type' => 'text',
        ),
        array(
            'key' => 'field_selling',
            'label' => 'อัตราขาย',
            'name' => 'selling',
            'type' => 'text',
        ),
        array(
            'key' => 'field_flag_path',
            'label' => 'URL ธงชาติ',
            'name' => 'flag_path',
            'type' => 'url',
        ),
        array(
            'key' => 'field_last_updated',
            'label' => 'อัปเดตล่าสุด',
            'name' => 'last_updated',
            'type' => 'text',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'exchange_rate',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ),
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'page',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => 'ฟิลด์สำหรับเก็บข้อมูลอัตราแลกเปลี่ยน - แสดงใน Post, Page, และ Exchange Rate',
));
