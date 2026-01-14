# Synergy Sidebar Widgets

Plugin สำหรับสร้าง Sidebar Widgets แบบ Minimal และเชี่ยวชาญ สำหรับเว็บไซต์บทความทางการเงิน

## 🎯 Shortcodes ที่ใช้ได้

### 1. บทความยอดนิยม
```
[synergy_popular_posts limit="5" show_thumbnail="yes" show_date="yes"]
```

**Parameters:**
- `limit` - จำนวนบทความ (ค่าเริ่มต้น: 5)
- `show_thumbnail` - แสดงรูปภาพ (yes/no)
- `show_date` - แสดงวันที่ (yes/no)

---

### 2. บทความล่าสุด
```
[synergy_recent_posts limit="5" show_thumbnail="yes" show_date="yes"]
```

**Parameters:**
- `limit` - จำนวนบทความ (ค่าเริ่มต้น: 5)
- `show_thumbnail` - แสดงรูปภาพ (yes/no)
- `show_date` - แสดงวันที่ (yes/no)

---

### 3. หมวดหมู่
```
[synergy_categories show_count="yes" orderby="count" limit="10"]
```

**Parameters:**
- `show_count` - แสดงจำนวนบทความ (yes/no)
- `orderby` - เรียงตาม (count/name)
- `order` - ลำดับ (DESC/ASC)
- `limit` - จำนวนหมวดหมู่ (ค่าเริ่มต้น: 10)

---

### 4. อัตราแลกเปลี่ยนแบบกระชับ
```
[synergy_exchange_mini currencies="USD,EUR,GBP,JPY" show_flags="yes"]
```

**Parameters:**
- `currencies` - สกุลเงินที่จะแสดง (คั่นด้วย comma)
- `show_flags` - แสดงธงชาติ (yes/no)

**หมายเหตุ:** ต้องติดตั้ง `growfox-rest-api` plugin ก่อน

---

### 5. ราคาทองแบบย่อ
```
[synergy_gold_mini]
```

**หมายเหตุ:** ต้องติดตั้ง `growfox-rest-api` plugin ก่อน

---

### 6. ฟอร์มสมัครรับข่าวสาร
```
[synergy_newsletter title="รับข่าวสารล่าสุด" description="สมัครรับบทความและข้อมูลการเงินทุกวัน"]
```

**Parameters:**
- `title` - หัวข้อ
- `description` - คำอธิบาย

---

## 📝 วิธีใช้งาน

### ใน Kubio Theme (synergycrafted.com)

1. ไปที่หน้าที่ต้องการเพิ่ม Sidebar
2. เพิ่ม **Column** (2 columns: 70% + 30%)
3. ใน Column ขวา (30%) เพิ่ม **Shortcode Block**
4. ใส่ shortcode ที่ต้องการ

**ตัวอย่าง Sidebar เต็ม:**

```
[synergy_popular_posts limit="5"]

[synergy_exchange_mini currencies="USD,EUR,GBP"]

[synergy_gold_mini]

[synergy_categories limit="8"]

[synergy_newsletter]
```

---

## 🎨 การปรับแต่ง CSS

ถ้าต้องการปรับแต่งสี สามารถเพิ่ม Custom CSS ใน Theme:

```css
/* เปลี่ยนสีหลัก */
.synergy-widget {
    border-left: 3px solid #3498db;
}

/* เปลี่ยนสีปุ่ม */
.synergy-submit-btn {
    background: #27ae60;
}

/* เปลี่ยนสีลิงก์ */
.synergy-post-title a:hover {
    color: #e74c3c;
}
```

---

## 🔧 ความต้องการ

- WordPress 5.0+
- PHP 7.4+
- Kubio Theme (แนะนำ)
- growfox-rest-api plugin (สำหรับ exchange_mini และ gold_mini)

---

## 📦 การติดตั้ง

1. อัพโหลด `synergy-sidebar-widgets.zip` ใน WordPress
2. ไปที่ **Plugins > Add New > Upload Plugin**
3. เลือกไฟล์ ZIP
4. คลิก **Install Now** แล้ว **Activate**
5. ใช้ shortcodes ในหน้าที่ต้องการ

---

## 💡 Tips

1. **Sidebar ด้านขวา:** ใช้ Column 70% + 30%
2. **Sidebar ด้านซ้าย:** ใช้ Column 30% + 70%
3. **เรียงลำดับ:** ใส่ shortcode ที่สำคัญที่สุดไว้ด้านบน
4. **Mobile:** Sidebar จะแสดงด้านล่างเนื้อหาอัตโนมัติ

---

## 🎯 แนะนำสำหรับเว็บการเงิน

**Sidebar ที่ดีที่สุด:**

1. บทความยอดนิยม (ดึงดูดผู้อ่าน)
2. อัตราแลกเปลี่ยน (ข้อมูลสด)
3. ราคาทอง (ข้อมูลสด)
4. หมวดหมู่ (Navigation)
5. Newsletter (สร้าง Email List)

---

Version: 1.0.0
Author: Synergy Crafted
