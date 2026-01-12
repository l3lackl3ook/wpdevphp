<?php
if (!defined('ABSPATH')) exit;

/**
 * ACF Fields สำหรับเก็บข้อมูลอัตราแลกเปลี่ยน
 * 
 * หมายเหตุ: ไม่มี ACF Options Page (เมนู "อัตราแลกเปลี่ยน")
 * เพราะไม่จำเป็น - Growfox Dynamic Tags อ่านข้อมูลจากฐานข้อมูลโดยตรง
 * 
 * ข้อมูลถูกเก็บใน wp_options table:
 * - options_exchange_data (JSON ทั้งหมด)
 * - options_period (วันที่)
 * - options_last_update (เวลาอัปเดต)
 */

// Helper function: ดึงอัตราแลกเปลี่ยนตามสกุลเงิน
function growfox_get_rate($currency, $type = 'selling') {
    $data = get_field('exchange_data', 'option');
    if (!$data) return null;
    
    $rates = json_decode($data, true);
    if (!$rates) return null;
    
    foreach ($rates as $rate) {
        if (strtoupper($rate['currency_id']) === strtoupper($currency)) {
            return $rate[$type] ?? null;
        }
    }
    
    return null;
}
