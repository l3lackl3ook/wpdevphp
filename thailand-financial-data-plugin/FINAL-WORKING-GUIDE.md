# 🎯 คู่มือใช้งานจริง - ทำตามนี้แล้วใช้งานได้แน่นอน

## ✅ สิ่งที่คุณมีอยู่แล้ว
- ✅ Plugin: thailand-financial-data-plugin-v1.0.0.zip (ติดตั้งแล้ว)
- ✅ API Endpoints พร้อมใช้งาน:
  - `/wp-json/thailand-financial/v1/exchange-rates`
  - `/wp-json/thailand-financial/v1/oil-prices`
  - `/wp-json/thailand-financial/v1/gold-prices`

## 🚀 ขั้นตอนการทำงาน (ไม่ต้องแก้โค้ด)

---

## 📊 Scenario 1: อัตราแลกเปลี่ยน (ใช้ Scenario เดิมที่มีอยู่)

### ✅ ใช้ Scenario ของ Growfox ที่มีอยู่แล้ว

**แค่เปลี่ยน HTTP 2 (ส่งไป WordPress):**

#### HTTP 2 - ส่งไป Thailand Financial Data

**URL:**
```
https://blogeverydayth.com/wp-json/thailand-financial/v1/exchange-rates
```

**Method:** POST

**Headers:**
- Name: `Content-Type`
- Value: `application/json`

**Body type:** Raw

**Content type:** JSON (application/json)

**Request content:**
```json
{
  "USD": {
    "buy": "{{1.data.responseContent[0].buying_sight}}",
    "sell": "{{1.data.responseContent[0].selling}}"
  },
  "EUR": {
    "buy": "{{1.data.responseContent[1].buying_sight}}",
    "sell": "{{1.data.responseContent[1].selling}}"
  },
  "GBP": {
    "buy": "{{1.data.responseContent[2].buying_sight}}",
    "sell": "{{1.data.responseContent[2].selling}}"
  },
  "JPY": {
    "buy": "{{1.data.responseContent[3].buying_sight}}",
    "sell": "{{1.data.responseContent[3].selling}}"
  },
  "CAD": {
    "buy": "{{1.data.responseContent[4].buying_sight}}",
    "sell": "{{1.data.responseContent[4].selling}}"
  },
  "AUD": {
    "buy": "{{1.data.responseContent[5].buying_sight}}",
    "sell": "{{1.data.responseContent[5].selling}}"
  },
  "INR": {
    "buy": "{{1.data.responseContent[6].buying_sight}}",
    "sell": "{{1.data.responseContent[6].selling}}"
  }
}
```

**Parse response:** No

---

## ⛽ Scenario 2: ราคาน้ำมัน (สร้างใหม่)

### Module 1: Schedule
- **Run:** Every day at 06:00

### Module 2: HTTP - ดึงข้อมูลจาก Bangchak

**URL:**
```
https://oil-price.bangchak.co.th/BcpOilPrice1/th
```

**Method:** GET

**Headers:**
- **Item 1:**
  - Name: `User-Agent`
  - Value: `Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36`

**Parse response:** Yes

### Module 3-9: Text Parser (แยกราคาแต่ละชนิด)

#### Module 3: แก๊สโซฮอล์ 91

**Text:** `{{2.data}}`

**Pattern:**
```
แก๊สโซฮอล์ 91.*?<td[^>]*>(\d+\.\d+)</td>\s*<td[^>]*>(\d+\.\d+)</td>
```

**Global match:** No

#### Module 4: แก๊สโซฮอล์ 95

**Text:** `{{2.data}}`

**Pattern:**
```
แก๊สโซฮอล์ 95.*?<td[^>]*>(\d+\.\d+)</td>\s*<td[^>]*>(\d+\.\d+)</td>
```

#### Module 5: E85

**Text:** `{{2.data}}`

**Pattern:**
```
E85.*?<td[^>]*>(\d+\.\d+)</td>\s*<td[^>]*>(\d+\.\d+)</td>
```

#### Module 6: E20

**Text:** `{{2.data}}`

**Pattern:**
```
E20.*?<td[^>]*>(\d+\.\d+)</td>\s*<td[^>]*>(\d+\.\d+)</td>
```

#### Module 7: ไฮพรีเมียมดีเซล

**Text:** `{{2.data}}`

**Pattern:**
```
ไฮพรีเมียมดีเซล.*?<td[^>]*>(\d+\.\d+)</td>\s*<td[^>]*>(\d+\.\d+)</td>
```

#### Module 8: ไฮดีเซล (ไบโอดีเซล)

**Text:** `{{2.data}}`

**Pattern:**
```
ไฮดีเซล.*?<td[^>]*>(\d+\.\d+)</td>\s*<td[^>]*>(\d+\.\d+)</td>
```

#### Module 9: แก๊สโซฮอล์ 97

**Text:** `{{2.data}}`

**Pattern:**
```
97.*?<td[^>]*>(\d+\.\d+)</td>\s*<td[^>]*>(\d+\.\d+)</td>
```

### Module 10: HTTP - ส่งไป WordPress

**URL:**
```
https://blogeverydayth.com/wp-json/thailand-financial/v1/oil-prices
```

**Method:** POST

**Headers:**
- Name: `Content-Type`
- Value: `application/json`

**Request content:**
```json
{
  "gasohol_91": {
    "today": "{{3.1}}",
    "tomorrow": "{{3.2}}"
  },
  "gasohol_95": {
    "today": "{{4.1}}",
    "tomorrow": "{{4.2}}"
  },
  "e85": {
    "today": "{{5.1}}",
    "tomorrow": "{{5.2}}"
  },
  "e20": {
    "today": "{{6.1}}",
    "tomorrow": "{{6.2}}"
  },
  "diesel_premium": {
    "today": "{{7.1}}",
    "tomorrow": "{{7.2}}"
  },
  "biodiesel": {
    "today": "{{8.1}}",
    "tomorrow": "{{8.2}}"
  },
  "gasohol_97": {
    "today": "{{9.1}}",
    "tomorrow": "{{9.2}}"
  }
}
```

---

## 🏆 Scenario 3: ราคาทอง (สร้างใหม่)

### Module 1: Schedule
- **Run:** Every 30 minutes

### Module 2: HTTP - ดึงข้อมูลจาก ทองคำราคา.com

**URL:**
```
https://xn--42cah7d0cxcvbbb9x.com
```

**Method:** GET

**Headers:**
- **Item 1:**
  - Name: `User-Agent`
  - Value: `Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36`

**Parse response:** Yes

### Module 3: Text Parser - ทองคำแท่ง รับซื้อ

**Text:** `{{2.data}}`

**Pattern:**
```
ทองคำแท่ง.*?<td[^>]*class="em bg-em g-u">([0-9,]+\.\d+)</td>
```

**Global match:** No

### Module 4: Text Parser - ทองคำแท่ง ขายออก

**Text:** `{{2.data}}`

**Pattern:**
```
ทองคำแท่ง.*?<td[^>]*class="em bg-em g-u">[0-9,]+\.\d+</td>\s*<td[^>]*class="em bg-em g-u">([0-9,]+\.\d+)</td>
```

### Module 5: Text Parser - ทองรูปพรรณ รับซื้อ

**Text:** `{{2.data}}`

**Pattern:**
```
ทองรูปพรรณ.*?<td[^>]*class="em bg-em g-u">([0-9,]+\.\d+)</td>
```

### Module 6: Text Parser - ทองรูปพรรณ ขายออก

**Text:** `{{2.data}}`

**Pattern:**
```
ทองรูปพรรณ.*?<td[^>]*class="em bg-em g-u">[0-9,]+\.\d+</td>\s*<td[^>]*class="em bg-em g-u">([0-9,]+\.\d+)</td>
```

### Module 7: Text Parser - เปลี่ยนแปลง

**Text:** `{{2.data}}`

**Pattern:**
```
วันนี้.*?<span[^>]*>([+\-]?\d+)</span>
```

### Module 8: HTTP - ส่งไป WordPress

**URL:**
```
https://blogeverydayth.com/wp-json/thailand-financial/v1/gold-prices
```

**Method:** POST

**Headers:**
- Name: `Content-Type`
- Value: `application/json`

**Request content:**
```json
{
  "bar_buy": "{{3.1}}",
  "bar_sell": "{{4.1}}",
  "jewelry_buy": "{{5.1}}",
  "jewelry_sell": "{{6.1}}",
  "change": "{{7.1}}"
}
```

---

## 🎨 การแสดงผลใน Elementor

### 1. เปิด Elementor Editor

### 2. เพิ่ม Text Editor Widget

### 3. คลิกไอคอน Dynamic Tags (🗄️)

### 4. เลือก "thailand-financial"

### 5. เลือกประเภทข้อมูล:

#### แสดงราคา USD ซื้อ:
- Dynamic Tag: **อัตราแลกเปลี่ยน**
- สกุลเงิน: **USD**
- ประเภท: **ราคาซื้อ**

#### แสดงราคาแก๊สโซฮอล์ 91:
- Dynamic Tag: **ราคาน้ำมัน**
- ชนิดน้ำมัน: **แก๊สโซฮอล์ 91 S EVO**
- วัน: **วันนี้**

#### แสดงราคาทองคำแท่ง:
- Dynamic Tag: **ราคาทอง**
- ประเภททอง: **ทองคำแท่ง - รับซื้อ**

---

## 🔍 การทดสอบ

### 1. ทดสอบว่า Plugin ทำงาน

เปิดเบราว์เซอร์:
```
https://blogeverydayth.com/wp-json/thailand-financial/v1/exchange-rates
```

ควรเห็น:
```json
{
  "success": true,
  "data": [],
  "updated_at": ""
}
```

### 2. ทดสอบส่งข้อมูลด้วย curl

```bash
curl -X POST https://blogeverydayth.com/wp-json/thailand-financial/v1/exchange-rates \
  -H "Content-Type: application/json" \
  -d '{
    "USD": {"buy": "32.46", "sell": "32.79"}
  }'
```

### 3. ตรวจสอบใน WordPress Admin

ไปที่: **WordPress Admin → Financial Data**

ควรเห็นข้อมูลที่ส่งมา

---

## ✅ สรุปทั้งหมด

### ไม่ต้องแก้โค้ด Plugin เลย!

แค่สร้าง 3 Scenarios ใน Make.com:

1. **Scenario 1:** อัตราแลกเปลี่ยน (ใช้ของเดิม แค่เปลี่ยน URL)
2. **Scenario 2:** ราคาน้ำมัน (สร้างใหม่)
3. **Scenario 3:** ราคาทอง (สร้างใหม่)

### ข้อมูลจะแสดงใน:
- ✅ WordPress Admin Dashboard
- ✅ Elementor Dynamic Tags
- ✅ Shortcodes

### ตารางการอัปเดต:
- อัตราแลกเปลี่ยน: ทุกวัน 09:00
- ราคาน้ำมัน: ทุกวัน 06:00
- ราคาทอง: ทุก 30 นาที

---

## 🆘 แก้ปัญหา

### ถ้า Text Parser ไม่ได้ข้อมูล

1. รัน HTTP module แล้วดู response
2. คัดลอก HTML ที่ได้
3. ทดสอบ Pattern ที่ https://regex101.com
4. ปรับ Pattern ให้ตรงกับ HTML จริง

### ถ้าข้อมูลไม่แสดงใน Elementor

1. ตรวจสอบว่า Make.com ส่งข้อมูลสำเร็จ (Status 200)
2. เข้า WordPress Admin → Financial Data → ดูว่ามีข้อมูล
3. ลอง Refresh Elementor Editor
4. ตรวจสอบว่าเลือก Dynamic Tag ถูกต้อง

---

## 🎉 เสร็จสิ้น!

ตอนนี้คุณมีระบบดึงข้อมูลทางการเงินอัตโนมัติแล้ว! 🚀
