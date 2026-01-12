# 📚 คู่มือ WordPress Theme และ Plugins ในโปรเจ็คนี้

## 📁 โครงสร้างไฟล์ WordPress ในโปรเจ็ค

โปรเจ็คนี้มีไฟล์ที่เกี่ยวข้องกับ WordPress แบ่งเป็น 3 ส่วนหลัก:

### 1. 🎨 **Hello Elementor Child Theme**
**ตำแหน่ง:** `hello-elementor-child/`

**ไฟล์ทั้งหมด:**
- `functions.php` - ฟังก์ชันหลักของ theme (ดึงข้อมูลอัตราแลกเปลี่ยนจาก ธปท.)
- `style.css` - CSS สำหรับการออกแบบ theme
- `custom.js` - JavaScript สำหรับ theme
- `README.txt` - คู่มือการใช้งาน theme

**คุณสมบัติหลัก:**
- ✅ ระบบดึงข้อมูลอัตราแลกเปลี่ยนจาก ธนาคารแห่งประเทศไทย (BoT) API
- ✅ Custom Post Type สำหรับเก็บข้อมูลอัตราแลกเปลี่ยน
- ✅ Shortcode `[bot_exchange_rates]` แสดงตารางอัตราแลกเปลี่ยน
- ✅ อัปเดตข้อมูลอัตโนมัติทุกวัน (WordPress Cron)
- ✅ หน้า Admin สำหรับอัปเดตข้อมูลด้วยตนเอง
- ✅ การออกแบบที่สวยงามและ Responsive
- ✅ Reading Time, Social Share Buttons, Related Posts
- ✅ Breadcrumbs, SEO Meta Tags, Lazy Loading
- ✅ Sticky Header with Scroll Detection

**วิธีใช้งาน:**
1. ติดตั้ง Hello Elementor parent theme ก่อน
2. อัปโหลด child theme นี้
3. ไปที่ "อัตราแลกเปลี่ยน" → "อัปเดตข้อมูล"
4. คลิก "อัปเดตข้อมูลทันที"
5. ใช้ shortcode `[bot_exchange_rates]` ในหน้าที่ต้องการ

---

### 2. 🔌 **Thailand Financial Data Plugin**
**ตำแหน่ง:** `thailand-financial-data-plugin/`

**โครงสร้างไฟล์:**
```
thailand-financial-data-plugin/
├── thailand-financial-data.php          # ไฟล์หลักของ plugin
├── includes/
│   ├── admin-menu.php                   # หน้า Admin Panel
│   ├── rest-api.php                     # REST API endpoints
│   ├── elementor-integration.php        # Elementor widgets
│   ├── shortcodes.php                   # Shortcodes
│   └── dynamic-tags/
│       ├── exchange-rate-tag.php        # Dynamic Tag อัตราแลกเปลี่ยน
│       ├── gold-price-tag.php           # Dynamic Tag ราคาทอง
│       └── oil-price-tag.php            # Dynamic Tag ราคาน้ำมัน
└── เอกสาร/
    ├── README.md                        # คู่มือหลัก
    ├── QUICK-START.md                   # เริ่มต้นใช้งานเร็ว
    ├── FINAL-WORKING-GUIDE.md           # คู่มือการทำงาน
    ├── MAKE-SETUP-GUIDE.md              # คู่มือติดตั้ง Make.com
    ├── MAKE-CONFIG-SIMPLE.md            # การตั้งค่า Make.com
    ├── MAKE-SCENARIOS-WITH-REAL-API.md  # Scenarios สำหรับ Make.com
    └── SIMPLE-SOLUTION.md               # วิธีแก้ปัญหาง่ายๆ
```

**คุณสมบัติหลัก:**
- ✅ ดึงข้อมูลทางการเงินจาก Make.com webhook
- ✅ รองรับ: อัตราแลกเปลี่ยน, ราคาทอง, ราคาน้ำมัน
- ✅ REST API endpoints สำหรับเข้าถึงข้อมูล
- ✅ Elementor Dynamic Tags (แสดงข้อมูลแบบ real-time)
- ✅ Elementor Widgets สำหรับแสดงตาราง
- ✅ Shortcodes สำหรับใช้งานง่าย
- ✅ Admin Panel สำหรับจัดการข้อมูล

**API Endpoints:**
- `GET /wp-json/thailand-financial/v1/exchange-rates` - ดึงอัตราแลกเปลี่ยน
- `GET /wp-json/thailand-financial/v1/gold-prices` - ดึงราคาทอง
- `GET /wp-json/thailand-financial/v1/oil-prices` - ดึงราคาน้ำมัน
- `POST /wp-json/thailand-financial/v1/webhook` - รับข้อมูลจาก Make.com

**Shortcodes:**
- `[exchange_rates]` - แสดงตารางอัตราแลกเปลี่ยน
- `[gold_prices]` - แสดงราคาทอง
- `[oil_prices]` - แสดงราคาน้ำมัน

---

### 3. 🔌 **GrowFox REST API Plugin**
**ตำแหน่ง:** `growfox-rest-api-fixed/`

**โครงสร้างไฟล์:**
```
growfox-rest-api-fixed/
├── growfox-rest-api.php                      # ไฟล์หลักของ plugin
├── includes/
│   ├── acf-fields.php                        # ACF Field Groups
│   ├── elementor-dynamic-tags.php            # Dynamic Tags สำหรับ Elementor
│   ├── dynamic-tag-exchange-rate.php         # Tag อัตราแลกเปลี่ยน
│   ├── dynamic-tag-period.php                # Tag วันที่
│   ├── elementor-widget-exchange-table.php   # Widget ตารางอัตราแลกเปลี่ยน
│   └── hide-acf-tags.php                     # ซ่อน ACF tags ที่ไม่ใช้
├── assets/                                   # CSS/JS files
└── เอกสาร/
    ├── README.md                             # คู่มือหลัก
    ├── MAKE-COMPLETE-GUIDE.md                # คู่มือครบถ้วน
    ├── MAKE-SETUP-GUIDE.md                   # คู่มือติดตั้ง Make.com
    ├── DYNAMIC-TAGS-EXPLAINED.md             # อธิบาย Dynamic Tags
    ├── SHOW-IN-FIELD-GROUPS.md               # แสดง Field Groups
    ├── WHY-ACF-TAGS-SHOW.md                  # ทำไม ACF Tags แสดง
    ├── WHY-NO-ACF-MENU.md                    # ทำไมไม่มี ACF Menu
    ├── FIX-EMPTY-CURRENCY-DROPDOWN.md        # แก้ dropdown ว่าง
    ├── FIX-FRONTEND-SHOWS-DASHES.md          # แก้ปัญหาแสดง dashes
    └── DEBUG-NO-DATA.md                      # Debug ไม่มีข้อมูล
```

**คุณสมบัติหลัก:**
- ✅ ใช้ ACF (Advanced Custom Fields) เก็บข้อมูล
- ✅ Elementor Dynamic Tags สำหรับแสดงข้อมูลแบบ dynamic
- ✅ Elementor Widget แสดงตารางอัตราแลกเปลี่ยน
- ✅ รองรับการแสดงผลแบบ real-time
- ✅ ซ่อน ACF tags ที่ไม่จำเป็น

**Dynamic Tags ที่มี:**
- Exchange Rate Tag - แสดงอัตราแลกเปลี่ยนตามสกุลเงิน
- Period Tag - แสดงวันที่ของข้อมูล

---

### 4. 📄 **ไฟล์เสริมอื่นๆ**

**ACF Configuration:**
- `acf-fields-new.php` - การตั้งค่า ACF Field Groups แบบ code

**Scripts:**
- `sticky-header-script.php` - Plugin สำหรับ Sticky Header

---

## 🚀 วิธีแยกไฟล์ออกมาใช้งาน

### สำหรับ Theme (Hello Elementor Child):
```bash
# คัดลอกไฟล์ theme ไปยัง Desktop
cp -r hello-elementor-child ~/Desktop/hello-elementor-child-export
```

**ไฟล์ที่ต้องนำไป:**
- `hello-elementor-child/functions.php`
- `hello-elementor-child/style.css`
- `hello-elementor-child/custom.js`
- `hello-elementor-child/README.txt`

### สำหรับ Plugin (Thailand Financial Data):
```bash
# คัดลอก plugin ไปยัง Desktop
cp -r thailand-financial-data-plugin ~/Desktop/thailand-financial-data-plugin-export
```

### สำหรับ Plugin (GrowFox REST API):
```bash
# คัดลอก plugin ไปยัง Desktop
cp -r growfox-rest-api-fixed ~/Desktop/growfox-rest-api-export
```

---

## 📦 การสร้าง ZIP สำหรับติดตั้ง

### สร้าง ZIP สำหรับ Theme:
```bash
cd ~/Desktop
zip -r hello-elementor-child.zip hello-elementor-child-export/
```

### สร้าง ZIP สำหรับ Plugins:
```bash
cd ~/Desktop
zip -r thailand-financial-data-plugin.zip thailand-financial-data-plugin-export/
zip -r growfox-rest-api.zip growfox-rest-api-export/
```

---

## 🔧 ความต้องการของระบบ

**สำหรับ Theme:**
- WordPress 5.0+
- Hello Elementor parent theme
- PHP 7.4+

**สำหรับ Plugins:**
- WordPress 5.0+
- Elementor (แนะนำ Pro)
- Advanced Custom Fields (สำหรับ GrowFox plugin)
- PHP 7.4+

---

## 📝 หมายเหตุสำคัญ

1. **Hello Elementor Child Theme** - ใช้สำหรับดึงข้อมูลจาก BoT API โดยตรง (ไม่ต้องใช้ Make.com)
2. **Thailand Financial Data Plugin** - ใช้สำหรับรับข้อมูลจาก Make.com webhook (ยืดหยุ่นกว่า)
3. **GrowFox REST API Plugin** - ใช้ ACF เก็บข้อมูล เหมาะสำหรับการจัดการข้อมูลที่ซับซ้อน

**เลือกใช้ตามความเหมาะสม:**
- ถ้าต้องการความง่าย → ใช้ Theme
- ถ้าต้องการความยืดหยุ่น → ใช้ Thailand Financial Data Plugin
- ถ้าต้องการจัดการข้อมูลซับซ้อน → ใช้ GrowFox REST API Plugin

---

## 🆘 การแก้ปัญหา

### ปัญหา: ไม่สามารถดึงข้อมูลจาก BoT API
**วิธีแก้:**
1. ตรวจสอบว่าเซิร์ฟเวอร์เชื่อมต่ออินเทอร์เน็ตได้
2. เปิด `allow_url_fopen` ใน PHP
3. ตรวจสอบ SSL certificate
4. ดู error log ที่ `wp-content/debug.log`

### ปัญหา: ACF Fields ไม่แสดง
**วิธีแก้:**
1. ติดตั้ง ACF plugin
2. ตรวจสอบว่า `acf-fields-new.php` ถูก include
3. Clear cache

### ปัญหา: Dynamic Tags ไม่ทำงาน
**วิธีแก้:**
1. ตรวจสอบว่าติดตั้ง Elementor แล้ว
2. อัปเดตข้อมูลใน Admin Panel
3. Clear Elementor cache

---

## 📞 ติดต่อ

สร้างโดย: **blogeverydayth.com**
อัปเดตล่าสุด: **2025**
