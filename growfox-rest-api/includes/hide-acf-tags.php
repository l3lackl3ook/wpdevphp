<?php
if (!defined('ABSPATH')) exit;

/**
 * ซ่อน ACF Dynamic Tags ที่ไม่จำเป็น
 * 
 * เหตุผล:
 * - ACF Plugin สร้าง Dynamic Tags ให้อัตโนมัติ
 * - แต่แสดง JSON raw ไม่สวย
 * - ใช้ Growfox Dynamic Tags แทน
 */

add_filter('acf/get_field_groups', function($field_groups) {
    // ถ้าอยู่ใน Elementor Editor
    if (isset($_GET['action']) && $_GET['action'] === 'elementor') {
        // กรอง Field Groups ที่ไม่ต้องการแสดงใน Dynamic Tags
        $field_groups = array_filter($field_groups, function($group) {
            // ซ่อน Field Group ของ Growfox
            return $group['key'] !== 'group_exchange_rates';
        });
    }
    
    return $field_groups;
});

// ซ่อน ACF Dynamic Tags Category ถ้าไม่มี Fields
add_action('elementor/dynamic_tags/register', function($dynamic_tags) {
    // ไม่ต้องทำอะไร - แค่ให้ Growfox Tags แสดงเท่านั้น
}, 999);
