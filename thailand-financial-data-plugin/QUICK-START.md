# ⚡ Quick Start Guide

## 🚀 ติดตั้ง Plugin (5 นาที)

### 1. อัปโหลด Plugin
```bash
# อัปโหลดโฟลเดอร์ thailand-financial-data-plugin ไปที่:
/wp-content/plugins/thailand-financial-data-plugin
```

### 2. เปิดใช้งาน
1. เข้า WordPress Admin
2. ไปที่ **Plugins**
3. เปิดใช้งาน **"Thailand Financial Data"**
4. เมนู **"Financial Data"** จะปรากฏ

### 3. คัดลอก API URLs
เข้า **Financial Data** → คัดลอก URLs เหล่านี้:
- อัตราแลกเปลี่ยน: `https://yoursite.com/wp-json/thailand-financial/v1/exchange-rates`
- ราคาน้ำมัน: `https://yoursite.com/wp-json/thailand-financial/v1/oil-prices`
- ราคาทอง: `https://yoursite.com/wp-json/thailand-financial/v1/gold-prices`

---

## 🔧 ตั้งค่า Make.com (10 นาที)

### Scenario 1: อัตราแลกเปลี่ยน

```
1. Schedule (ทุกวัน 09:00)
   ↓
2. HTTP GET → https://aommoney.com
   ↓
3. Text Parser (แยกข้อมูล USD, EUR, GBP, etc.)
   ↓
4. HTTP POST → https://yoursite.com/wp-json/thailand-financial/v1/exchange-rates
   Body: {
     "USD": {"buy": "32.46", "sell": "32.79"},
     "EUR": {"buy": "37.65", "sell": "38.35"}
   }
```

### Scenario 2: ราคาน้ำมัน

```
1. Schedule (ทุกวัน 06:00)
   ↓
2. HTTP GET → https://aommoney.com
   ↓
3. Text Parser (แยกราคาน้ำมัน)
   ↓
4. HTTP POST → https://yoursite.com/wp-json/thailand-financial/v1/oil-prices
   Body: {
     "gasohol_91": {"today": "31.48", "tomorrow": "31.48"}
   }
```

### Scenario 3: ราคาทอง

```
1. Schedule (ทุก 30 นาที)
   ↓
2. HTTP GET → https://aommoney.com
   ↓
3. Text Parser (แยกราคาทอง)
   ↓
4. HTTP POST → https://yoursite.com/wp-json/thailand-financial/v1/gold-prices
   Body: {
     "bar_buy": "60550",
     "bar_sell": "60650",
     "change": "+750"
   }
```

---

## 🎨 แสดงข้อมูลใน Elementor (2 นาที)

### วิธีที่ 1: Dynamic Tags

1. เปิด Elementor Editor
2. เพิ่ม **Text Editor** widget
3. คลิกไอคอน **Dynamic Tags** (🗄️)
4. เลือก **"thailand-financial"**
5. เลือกประเภทข้อมูล:
   - **อัตราแลกเปลี่ยน** (USD, EUR, GBP, etc.)
   - **ราคาน้ำมัน** (แก๊สโซฮอล์ 91, 95, E85, etc.)
   - **ราคาทอง** (ทองคำแท่ง, ทองรูปพรรณ)

### วิธีที่ 2: Shortcodes

```
[tfd_exchange_rate currency="USD" type="buy"]
[tfd_oil_price type="gasohol_91" day="today"]
[tfd_gold_price type="bar_buy"]
```

---

## 📊 ตัวอย่างการใช้งาน

### แสดงตารางอัตราแลกเปลี่ยน

```html
<table>
  <tr>
    <td>USD</td>
    <td>[tfd_exchange_rate currency="USD" type="buy"]</td>
    <td>[tfd_exchange_rate currency="USD" type="sell"]</td>
  </tr>
  <tr>
    <td>EUR</td>
    <td>[tfd_exchange_rate currency="EUR" type="buy"]</td>
    <td>[tfd_exchange_rate currency="EUR" type="sell"]</td>
  </tr>
</table>
```

### แสดงราคาน้ำมัน

```
แก๊สโซฮอล์ 91 วันนี้: [tfd_oil_price type="gasohol_91" day="today"] บาท
แก๊สโซฮอล์ 95 วันนี้: [tfd_oil_price type="gasohol_95" day="today"] บาท
```

### แสดงราคาทอง

```
ทองคำแท่ง รับซื้อ: [tfd_gold_price type="bar_buy"] บาท
ทองคำแท่ง ขายออก: [tfd_gold_price type="bar_sell"] บาท
เปลี่ยนแปลง: [tfd_gold_price type="change"] บาท
```

---

## 🔍 ตรวจสอบข้อมูล

### ใน WordPress Admin
1. ไปที่ **Financial Data**
2. ดูข้อมูลทั้งหมดและเวลาอัปเดตล่าสุด

### ผ่าน API (GET)
```bash
# ดูอัตราแลกเปลี่ยน
curl https://yoursite.com/wp-json/thailand-financial/v1/exchange-rates

# ดูราคาน้ำมัน
curl https://yoursite.com/wp-json/thailand-financial/v1/oil-prices

# ดูราคาทอง
curl https://yoursite.com/wp-json/thailand-financial/v1/gold-prices
```

---

## ⚙️ ตั้งค่า API Key (ไม่บังคับ)

### ใน WordPress
1. ไปที่ **Financial Data → ตั้งค่า**
2. ตั้ง API Key: `my-secret-key-12345`
3. บันทึก

### ใน Make.com
เพิ่ม Header ใน HTTP Request:
- **Key:** `X-API-Key`
- **Value:** `my-secret-key-12345`

---

## 📝 รายการ Dynamic Tags ทั้งหมด

### อัตราแลกเปลี่ยน
- USD, EUR, GBP, CAD, JPY, AUD, INR
- ประเภท: ราคาซื้อ, ราคาขาย

### ราคาน้ำมัน
- ไฮพรีเมียมดีเซล S
- ไฮดีเซล S (ไบโอดีเซล)
- ไฮพรีเมียม 97 แก๊สโซฮอล์ 95
- แก๊สโซฮอล์ E85 S EVO
- แก๊สโซฮอล์ E20 S EVO
- แก๊สโซฮอล์ 91 S EVO
- แก๊สโซฮอล์ 95 S EVO
- วัน: วันนี้, พรุ่งนี้

### ราคาทอง
- ทองคำแท่ง - รับซื้อ
- ทองคำแท่ง - ขายออก
- ทองรูปพรรณ - รับซื้อ
- ทองรูปพรรณ - ขายออก
- เปลี่ยนแปลง

---

## ✅ เสร็จสิ้น!

ตอนนี้คุณสามารถ:
- ✅ ดึงข้อมูลจาก aommoney.com อัตโนมัติ
- ✅ แสดงข้อมูลใน Elementor แบบ real-time
- ✅ ใช้ Shortcodes ในหน้าเว็บ
- ✅ ดูข้อมูลใน Admin Dashboard

**ต้องการความช่วยเหลือเพิ่มเติม?**
อ่าน `MAKE-SETUP-GUIDE.md` สำหรับคู่มือแบบละเอียด
