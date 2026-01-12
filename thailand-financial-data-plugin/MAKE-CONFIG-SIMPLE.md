# 🎯 การตั้งค่า Make.com แบบง่าย (ใช้ Scenario เดิม)

## 📌 สรุป: เปลี่ยนแค่ HTTP 2 (ส่งไป WordPress)

คุณมี Scenario เดิมอยู่แล้วสำหรับ Growfox:
```
HTTP 1 (GET) → ดึงข้อมูลจาก ธปท.
    ↓
HTTP 2 (POST) → ส่งไป Growfox API
```

สำหรับ **Thailand Financial Data** ให้เปลี่ยนแค่ **HTTP 2** เป็น:

---

## 🔧 HTTP 2 - ส่งอัตราแลกเปลี่ยนไป Thailand Financial Data

### ตั้งค่า HTTP 2

**URL:**
```
https://blogeverydayth.com/wp-json/thailand-financial/v1/exchange-rates
```

**Method:**
```
POST
```

**Headers:**
- **Item 1:**
  - Name: `Content-Type`
  - Value: `application/json`

**Body type:**
```
Raw
```

**Content type:**
```
JSON (application/json)
```

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

**Parse response:**
```
No
```

---

## 🎯 วิธีสร้าง Scenario ใหม่สำหรับราคาน้ำมัน

### Scenario: ดึงราคาน้ำมัน

#### 1. Schedule
- **Interval:** Every day at 06:00

#### 2. HTTP 1 - ดึงข้อมูลจาก aommoney.com

**URL:**
```
https://aommoney.com
```

**Method:**
```
GET
```

**Headers:**
- **Item 1:**
  - Name: `User-Agent`
  - Value: `Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36`

**Parse response:**
```
Yes
```

#### 3. Text Parser - แยกราคาแก๊สโซฮอล์ 91

**Module:** Text parser → Match pattern

**Text:**
```
{{2.data}}
```

**Pattern:**
```
แก๊สโซฮอล์ 91 S EVO.*?<div class="cl-2">([\d.]+)</div>\s*<div class="cl-3">([\d.]+)</div>
```

**Global match:** No

#### 4. Text Parser - แยกราคาแก๊สโซฮอล์ 95

**Text:**
```
{{2.data}}
```

**Pattern:**
```
แก๊สโซฮอล์ 95 S EVO.*?<div class="cl-2">([\d.]+)</div>\s*<div class="cl-3">([\d.]+)</div>
```

#### 5. Text Parser - แยกราคา E85

**Text:**
```
{{2.data}}
```

**Pattern:**
```
แก๊สโซฮอล์ E85 S EVO.*?<div class="cl-2">([\d.]+)</div>\s*<div class="cl-3">([\d.]+)</div>
```

#### 6. Text Parser - แยกราคา E20

**Text:**
```
{{2.data}}
```

**Pattern:**
```
แก๊สโซฮอล์ E20 S EVO.*?<div class="cl-2">([\d.]+)</div>\s*<div class="cl-3">([\d.]+)</div>
```

#### 7. Text Parser - แยกราคาดีเซล

**Text:**
```
{{2.data}}
```

**Pattern:**
```
ไฮพรีเมียมดีเซล S.*?<div class="cl-2">([\d.]+)</div>\s*<div class="cl-3">([\d.]+)</div>
```

#### 8. Text Parser - แยกราคาไบโอดีเซล

**Text:**
```
{{2.data}}
```

**Pattern:**
```
ไฮดีเซล S.*?<div class="cl-2">([\d.]+)</div>\s*<div class="cl-3">([\d.]+)</div>
```

#### 9. Text Parser - แยกราคาแก๊สโซฮอล์ 97

**Text:**
```
{{2.data}}
```

**Pattern:**
```
ไฮพรีเมียม 97.*?<div class="cl-2">([\d.]+)</div>\s*<div class="cl-3">([\d.]+)</div>
```

#### 10. HTTP 2 - ส่งไป WordPress

**URL:**
```
https://blogeverydayth.com/wp-json/thailand-financial/v1/oil-prices
```

**Method:**
```
POST
```

**Headers:**
- **Item 1:**
  - Name: `Content-Type`
  - Value: `application/json`

**Body type:**
```
Raw
```

**Content type:**
```
JSON (application/json)
```

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

## 🏆 Scenario: ดึงราคาทอง

### 1. Schedule
- **Interval:** Every 30 minutes

### 2. HTTP 1 - ดึงข้อมูลจาก aommoney.com

**URL:**
```
https://aommoney.com
```

**Method:**
```
GET
```

**Parse response:**
```
Yes
```

### 3. Text Parser - แยกราคาทองคำแท่ง รับซื้อ

**Text:**
```
{{2.data}}
```

**Pattern:**
```
<div class="gold-label">ราคาทองคำแท่ง</div>.*?รับซื้อ.*?<p class="g-price">([\d,]+)</p>
```

### 4. Text Parser - แยกราคาทองคำแท่ง ขายออก

**Text:**
```
{{2.data}}
```

**Pattern:**
```
ขายออก.*?<p class="g-price">([\d,]+)</p>
```

### 5. Text Parser - แยกเปลี่ยนแปลง

**Text:**
```
{{2.data}}
```

**Pattern:**
```
<div class="diff.*?<p class="num">([+\-\d,]+)</p>
```

### 6. HTTP 2 - ส่งไป WordPress

**URL:**
```
https://blogeverydayth.com/wp-json/thailand-financial/v1/gold-prices
```

**Method:**
```
POST
```

**Headers:**
- **Item 1:**
  - Name: `Content-Type`
  - Value: `application/json`

**Request content:**
```json
{
  "bar_buy": "{{3.1}}",
  "bar_sell": "{{4.1}}",
  "jewelry_buy": "60000",
  "jewelry_sell": "61150",
  "change": "{{5.1}}"
}
```

---

## ✅ สรุป

คุณจะมี **3 Scenarios** ทั้งหมด:

1. **อัตราแลกเปลี่ยน** (ใช้ Scenario เดิม แค่เปลี่ยน HTTP 2)
2. **ราคาน้ำมัน** (สร้างใหม่)
3. **ราคาทอง** (สร้างใหม่)

แต่ละ Scenario จะส่งข้อมูลไปที่ API endpoint ที่ต่างกัน:
- `/exchange-rates`
- `/oil-prices`
- `/gold-prices`
