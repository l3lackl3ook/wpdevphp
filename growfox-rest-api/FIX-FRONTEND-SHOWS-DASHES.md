# แก้ไข: หน้าเว็บแสดง -- แทนค่าจริง

## 🐛 ปัญหา

- **ใน Elementor Editor:** แสดง `32.4107` ✅
- **หน้าเว็บจริง (Frontend):** แสดง `--` ❌

## 🔍 สาเหตุ

โค้ดเดิมใช้:
```php
$data = get_field('exchange_data', 'option'); // ACF Function
```

แต่ข้อมูลจริงเก็บใน:
```php
$data = get_option('options_exchange_data'); // WordPress Options API
```

**ทำไมถึงทำงานใน Editor?**
- Elementor Editor โหลด ACF Plugin → `get_field()` ทำงาน
- Frontend ไม่โหลด ACF → `get_field()` return `null`

## ✅ วิธีแก้

### ไฟล์ที่แก้ไข

1. `includes/dynamic-tag-exchange-rate.php`
2. `includes/dynamic-tag-period.php`

### การเปลี่ยนแปลง

**เดิม:**
```php
$data = get_field('exchange_data', 'option');
```

**ใหม่:**
```php
$data = get_option('options_exchange_data');
```

## 📦 การอัปเดต

### ขั้นตอนที่ 1: อัปโหลด Plugin ใหม่

```
1. ลบ Plugin เดิม (Growfox REST API)
2. อัปโหลด growfox-rest-api-v2.6-fixed.zip
3. Activate Plugin
```

### ขั้นตอนที่ 2: ทดสอบ

```
1. ไปที่หน้าที่มี Dynamic Tags
2. กด Refresh (F5)
3. ตรวจสอบว่าแสดงค่าจริง (32.4107) แทน --
```

### ขั้นตอนที่ 3: Clear Cache (ถ้ามี)

```
1. Clear WordPress Cache
2. Clear Browser Cache (Ctrl+Shift+R)
3. Clear CDN Cache (ถ้ามี)
```

## 🎯 ผลลัพธ์

หลังอัปเดต:

```
┌─────────────────────────────────┐
│ อัตราแลกเปลี่ยนเงิน              │
├─────────────────────────────────┤
│ 32.4107                         │ ← แสดงค่าจริง ✅
│ 32.8398                         │ ← แสดงค่าจริง ✅
│ 27/10/2025                      │ ← แสดงวันที่ ✅
└─────────────────────────────────┘
```

## 🔍 Debug: ถ้ายังแสดง --

### ตรวจสอบข้อมูลในฐานข้อมูล

เพิ่มโค้ดนี้ใน `functions.php` ชั่วคราว:

```php
add_action('wp_footer', function() {
    if (current_user_can('administrator')) {
        $data = get_option('options_exchange_data');
        echo '<pre style="background:#000;color:#0f0;padding:20px;position:fixed;bottom:0;right:0;z-index:9999;max-width:400px;overflow:auto;">';
        echo 'Exchange Data: ';
        var_dump($data);
        echo '</pre>';
    }
});
```

**ผลลัพธ์ที่ควรเห็น:**
```
Exchange Data: string(5000) '[{"currency_id":"USD","selling":"32.4107",...}]'
```

**ถ้าเห็น:**
```
Exchange Data: bool(false)
```
= ข้อมูลยังไม่ถูกส่งมาจาก Make.com

### ตรวจสอบ Dynamic Tag

เพิ่มโค้ดนี้ใน `includes/dynamic-tag-exchange-rate.php`:

```php
public function render() {
    $settings = $this->get_settings();
    $currency = $settings['currency'];
    $field = $settings['field'];
    
    // DEBUG
    error_log('Currency: ' . $currency);
    error_log('Field: ' . $field);
    
    $data = get_option('options_exchange_data');
    error_log('Data: ' . ($data ? 'Found' : 'Not Found'));
    
    // ... rest of code
}
```

ดู Log ที่: `wp-content/debug.log`

## 📝 สรุป

**ปัญหา:** ใช้ `get_field()` (ACF) แทน `get_option()` (WordPress)

**วิธีแก้:** เปลี่ยนเป็น `get_option('options_exchange_data')`

**ผลลัพธ์:** Dynamic Tags ทำงานทั้ง Editor และ Frontend ✅
