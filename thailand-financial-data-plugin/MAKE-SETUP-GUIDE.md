# 🚀 คู่มือการตั้งค่า Make.com แบบละเอียด

## 📋 สิ่งที่ต้องเตรียม

1. ✅ ติดตั้ง Plugin "Thailand Financial Data" ใน WordPress แล้ว
2. ✅ มีบัญชี Make.com (ฟรีก็ได้)
3. ✅ URL ของเว็บไซต์คุณ (เช่น `https://yoursite.com`)

---

## 🎯 Scenario 1: ดึงอัตราแลกเปลี่ยน

### ขั้นตอนที่ 1: สร้าง Scenario ใหม่

1. เข้า Make.com → คลิก **"Create a new scenario"**
2. ตั้งชื่อ: `"ดึงอัตราแลกเปลี่ยน - aommoney.com"`

### ขั้นตอนที่ 2: เพิ่ม Schedule

1. คลิก **"+"** → เลือก **"Schedule"**
2. ตั้งค่า:
   - **Interval:** Every 1 hour (ทุก 1 ชั่วโมง)
   - หรือ Every day at 09:00 (ทุกวันเวลา 09:00)

### ขั้นตอนที่ 3: ดึงข้อมูลจาก aommoney.com

1. คลิก **"+"** → เลือก **"HTTP"** → **"Make a request"**
2. ตั้งค่า:
   - **URL:** `https://aommoney.com`
   - **Method:** GET
   - **Parse response:** Yes

### ขั้นตอนที่ 4: แยกข้อมูลจาก HTML

1. คลิก **"+"** → เลือก **"Text parser"** → **"Match pattern"**
2. ตั้งค่าสำหรับ **USD ราคาซื้อ**:
   - **Text:** `{{3.data}}`
   - **Pattern:** `<div class="cl-1">.*?USD</div>\s*<div class="cl-2">([\d.]+)</div>`
   - **Global match:** No

3. เพิ่ม Text parser อีก 1 ตัวสำหรับ **USD ราคาขาย**:
   - **Pattern:** `<div class="cl-1">.*?USD</div>\s*<div class="cl-2">[\d.]+</div>\s*<div class="cl-3">([\d.]+)</div>`

4. ทำซ้ำสำหรับสกุลเงินอื่นๆ (EUR, GBP, CAD, JPY, AUD, INR)

### ขั้นตอนที่ 5: สร้าง JSON Object

1. คลิก **"+"** → เลือก **"Tools"** → **"Set variable"**
2. สร้าง JSON:

```json
{
  "USD": {
    "buy": "{{4.1}}",
    "sell": "{{5.1}}"
  },
  "EUR": {
    "buy": "{{6.1}}",
    "sell": "{{7.1}}"
  },
  "GBP": {
    "buy": "{{8.1}}",
    "sell": "{{9.1}}"
  },
  "CAD": {
    "buy": "{{10.1}}",
    "sell": "{{11.1}}"
  },
  "JPY": {
    "buy": "{{12.1}}",
    "sell": "{{13.1}}"
  },
  "AUD": {
    "buy": "{{14.1}}",
    "sell": "{{15.1}}"
  },
  "INR": {
    "buy": "{{16.1}}",
    "sell": "{{17.1}}"
  }
}
```

### ขั้นตอนที่ 6: ส่งข้อมูลไป WordPress

1. คลิก **"+"** → เลือก **"HTTP"** → **"Make a request"**
2. ตั้งค่า:
   - **URL:** `https://yoursite.com/wp-json/thailand-financial/v1/exchange-rates`
   - **Method:** POST
   - **Headers:**
     - `Content-Type`: `application/json`
     - `X-API-Key`: `your-api-key` (ถ้าตั้งไว้)
   - **Body type:** Raw
   - **Request content:** `{{18.json}}`

### ขั้นตอนที่ 7: ทดสอบ

1. คลิก **"Run once"**
2. ตรวจสอบว่าข้อมูลส่งสำเร็จ (Status 200)
3. เข้า WordPress Admin → Financial Data → ดูข้อมูล

---

## ⛽ Scenario 2: ดึงราคาน้ำมัน

### ขั้นตอนที่ 1-3: เหมือน Scenario 1

### ขั้นตอนที่ 4: แยกข้อมูลราคาน้ำมัน

ใช้ Text parser แยกข้อมูลจาก HTML:

**Pattern สำหรับแต่ละชนิด:**
- ไฮพรีเมียมดีเซล: `<div class="cl-1"><img src=".*?133589693386477004.*?</div>\s*<div class="cl-2">([\d.]+)</div>\s*<div class="cl-3">([\d.]+)</div>`
- ไฮดีเซล S: `<div class="cl-1"><img src=".*?133589692550720467.*?</div>\s*<div class="cl-2">([\d.]+)</div>\s*<div class="cl-3">([\d.]+)</div>`

### ขั้นตอนที่ 5: สร้าง JSON

```json
{
  "diesel_premium": {
    "today": "{{4.1}}",
    "tomorrow": "{{4.2}}"
  },
  "biodiesel": {
    "today": "{{5.1}}",
    "tomorrow": "{{5.2}}"
  },
  "gasohol_97": {
    "today": "{{6.1}}",
    "tomorrow": "{{6.2}}"
  },
  "e85": {
    "today": "{{7.1}}",
    "tomorrow": "{{7.2}}"
  },
  "e20": {
    "today": "{{8.1}}",
    "tomorrow": "{{8.2}}"
  },
  "gasohol_91": {
    "today": "{{9.1}}",
    "tomorrow": "{{9.2}}"
  },
  "gasohol_95": {
    "today": "{{10.1}}",
    "tomorrow": "{{10.2}}"
  }
}
```

### ขั้นตอนที่ 6: ส่งไป WordPress

- **URL:** `https://yoursite.com/wp-json/thailand-financial/v1/oil-prices`
- **Method:** POST
- **Body:** JSON ด้านบน

---

## 🏆 Scenario 3: ดึงราคาทอง

### ขั้นตอนที่ 4: แยกข้อมูลราคาทอง

**Pattern:**
- ทองคำแท่ง รับซื้อ: `<div class="gold-label">ราคาทองคำแท่ง</div>.*?<p class="tag-title">รับซื้อ.*?<p class="g-price">([\d,]+)</p>`
- ทองคำแท่ง ขายออก: `<p class="tag-title">ขายออก.*?<p class="g-price">([\d,]+)</p>`
- เปลี่ยนแปลง: `<div class="diff.*?<p class="num">([+\-\d,]+)</p>`

### ขั้นตอนที่ 5: สร้าง JSON

```json
{
  "bar_buy": "{{4.1}}",
  "bar_sell": "{{5.1}}",
  "jewelry_buy": "60000",
  "jewelry_sell": "61150",
  "change": "{{6.1}}"
}
```

### ขั้นตอนที่ 6: ส่งไป WordPress

- **URL:** `https://yoursite.com/wp-json/thailand-financial/v1/gold-prices`
- **Method:** POST
- **Body:** JSON ด้านบน

---

## 🎨 วิธีแสดงข้อมูลใน Elementor

### 1. แสดงราคา USD

1. เปิด Elementor Editor
2. เพิ่ม **Text Editor** widget
3. พิมพ์: `ราคาซื้อ USD: `
4. คลิกไอคอน **Dynamic Tags** (รูปฐานข้อมูล)
5. เลือก **"thailand-financial"** → **"อัตราแลกเปลี่ยน"**
6. ตั้งค่า:
   - สกุลเงิน: **USD**
   - ประเภท: **ราคาซื้อ**
7. บันทึก

### 2. แสดงราคาแก๊สโซฮอล์ 91

1. เพิ่ม **Text Editor** widget
2. พิมพ์: `แก๊สโซฮอล์ 91: `
3. คลิก **Dynamic Tags**
4. เลือก **"ราคาน้ำมัน"**
5. ตั้งค่า:
   - ชนิดน้ำมัน: **แก๊สโซฮอล์ 91 S EVO**
   - วัน: **วันนี้**

### 3. แสดงราคาทองคำแท่ง

1. เพิ่ม **Text Editor** widget
2. พิมพ์: `ทองคำแท่ง รับซื้อ: `
3. คลิก **Dynamic Tags**
4. เลือก **"ราคาทอง"**
5. ตั้งค่า:
   - ประเภททอง: **ทองคำแท่ง - รับซื้อ**

---

## 🔍 การแก้ปัญหา

### ❌ ข้อมูลไม่แสดง (แสดง "-")

**สาเหตน:**
- Make.com ยังไม่ส่งข้อมูล
- Pattern ใน Text parser ผิด

**วิธีแก้:**
1. เข้า Make.com → Run scenario ด้วยตนเอง
2. ตรวจสอบว่า HTTP request ส่งสำเร็จ (Status 200)
3. เข้า WordPress Admin → Financial Data → ดูว่ามีข้อมูลหรือไม่

### ❌ Make.com ส่งข้อมูลไม่สำเร็จ (Error 400/401)

**สาเหตน:**
- URL ผิด
- API Key ไม่ตรงกัน
- JSON format ผิด

**วิธีแก้:**
1. ตรวจสอบ URL ว่าถูกต้อง
2. ตรวจสอบ API Key ใน WordPress Admin → Financial Data → ตั้งค่า
3. ตรวจสอบ JSON ว่าถูกต้อง (ใช้ jsonlint.com)

### ❌ HTML Pattern ไม่ตรงกับข้อมูล

**วิธีแก้:**
1. เปิด https://aommoney.com
2. กด F12 → Inspect Element
3. คัดลอก HTML ของส่วนที่ต้องการ
4. ปรับ Pattern ใน Text parser ให้ตรงกับ HTML

---

## 📅 ตารางการอัปเดต (แนะนำ)

| ประเภทข้อมูล | ความถี่ | เวลา |
|-------------|---------|------|
| อัตราแลกเปลี่ยน | ทุกวัน | 09:00 |
| ราคาน้ำมัน | ทุกวัน | 06:00 |
| ราคาทอง | ทุก 30 นาที | - |

---

## ✅ เสร็จสิ้น!

ตอนนี้คุณมี:
- ✅ Plugin ที่รับข้อมูลจาก Make.com
- ✅ Make.com Scenarios ที่ดึงข้อมูลอัตโนมัติ
- ✅ Dynamic Tags สำหรับแสดงข้อมูลใน Elementor
- ✅ Shortcodes สำหรับใช้ในหน้าเว็บ

ข้อมูลจะอัปเดตอัตโนมัติตามเวลาที่ตั้งไว้! 🎉
