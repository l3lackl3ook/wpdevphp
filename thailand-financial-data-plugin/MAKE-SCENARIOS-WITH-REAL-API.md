# 🎯 Make.com Scenarios - ใช้ API จริง (ไม่ต้อง scrape)

## ✅ ข้อดี
- ไม่โดนบลอกจาก Cloudflare/WAF
- ข้อมูลเหมือนกับ aommoney.com 100%
- เสถียร ไม่พังง่าย
- ฟรี ไม่เสียเงิน

---

## 📊 Scenario 1: อัตราแลกเปลี่ยน (ธปท.)

### Module 1: Schedule
- **Interval:** Every day at 09:00

### Module 2: HTTP - ดึงข้อมูลจาก ธปท.

**URL:**
```
https://www.bot.or.th/content/bot/th/statistics/exchange-rate/jcr:content/root/container/statisticstable2.results.level1cache.json
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

### Module 3: HTTP - ส่งไป WordPress

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
    "buy": "{{2.data.responseContent[0].buying_sight}}",
    "sell": "{{2.data.responseContent[0].selling}}"
  },
  "EUR": {
    "buy": "{{2.data.responseContent[1].buying_sight}}",
    "sell": "{{2.data.responseContent[1].selling}}"
  },
  "GBP": {
    "buy": "{{2.data.responseContent[2].buying_sight}}",
    "sell": "{{2.data.responseContent[2].selling}}"
  },
  "JPY": {
    "buy": "{{2.data.responseContent[3].buying_sight}}",
    "sell": "{{2.data.responseContent[3].selling}}"
  },
  "CAD": {
    "buy": "{{2.data.responseContent[4].buying_sight}}",
    "sell": "{{2.data.responseContent[4].selling}}"
  },
  "AUD": {
    "buy": "{{2.data.responseContent[5].buying_sight}}",
    "sell": "{{2.data.responseContent[5].selling}}"
  },
  "INR": {
    "buy": "{{2.data.responseContent[6].buying_sight}}",
    "sell": "{{2.data.responseContent[6].selling}}"
  }
}
```

**Parse response:**
```
No
```

---

## ⛽ Scenario 2: ราคาน้ำมัน (Bangchak API)

### Module 1: Schedule
- **Interval:** Every day at 06:00

### Module 2: HTTP - ดึงข้อมูลจาก Bangchak

**URL:**
```
https://oil-price.bangchak.co.th/api/oilprice
```

**Method:**
```
GET
```

**Headers:**
- **Item 1:**
  - Name: `User-Agent`
  - Value: `Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36`
- **Item 2:**
  - Name: `Accept`
  - Value: `application/json`

**Parse response:**
```
Yes
```

### Module 3: Iterator - วนลูปข้อมูลน้ำมัน

**Array:**
```
{{2.data}}
```

### Module 4: Router - แยกประเภทน้ำมัน

สร้าง 7 routes สำหรับแต่ละชนิด:

#### Route 1: แก๊สโซฮอล์ 91
**Filter:**
```
{{3.ProductName}} contains "แก๊สโซฮอล์ 91"
```

#### Route 2: แก๊สโซฮอล์ 95
**Filter:**
```
{{3.ProductName}} contains "แก๊สโซฮอล์ 95"
```

#### Route 3: E85
**Filter:**
```
{{3.ProductName}} contains "E85"
```

#### Route 4: E20
**Filter:**
```
{{3.ProductName}} contains "E20"
```

#### Route 5: ดีเซล
**Filter:**
```
{{3.ProductName}} contains "ไฮพรีเมียมดีเซล"
```

#### Route 6: ไบโอดีเซล
**Filter:**
```
{{3.ProductName}} contains "ไฮดีเซล"
```

#### Route 7: แก๊สโซฮอล์ 97
**Filter:**
```
{{3.ProductName}} contains "97"
```

### Module 5: Tools - สร้าง JSON

**Variable name:** `oil_data`

**Variable value:**
```json
{
  "gasohol_91": {
    "today": "{{route1.Price}}",
    "tomorrow": "{{route1.NextPrice}}"
  },
  "gasohol_95": {
    "today": "{{route2.Price}}",
    "tomorrow": "{{route2.NextPrice}}"
  },
  "e85": {
    "today": "{{route3.Price}}",
    "tomorrow": "{{route3.NextPrice}}"
  },
  "e20": {
    "today": "{{route4.Price}}",
    "tomorrow": "{{route4.NextPrice}}"
  },
  "diesel_premium": {
    "today": "{{route5.Price}}",
    "tomorrow": "{{route5.NextPrice}}"
  },
  "biodiesel": {
    "today": "{{route6.Price}}",
    "tomorrow": "{{route6.NextPrice}}"
  },
  "gasohol_97": {
    "today": "{{route7.Price}}",
    "tomorrow": "{{route7.NextPrice}}"
  }
}
```

### Module 6: HTTP - ส่งไป WordPress

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

**Request content:**
```
{{5.oil_data}}
```

---

## 🏆 Scenario 3: ราคาทอง (Gold Traders API)

### Module 1: Schedule
- **Interval:** Every 30 minutes

### Module 2: HTTP - ดึงข้อมูลจากสมาคมค้าทองคำ

**URL:**
```
https://www.goldtraders.or.th/api/goldapi/
```

**Method:**
```
GET
```

**Headers:**
- **Item 1:**
  - Name: `User-Agent`
  - Value: `Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36`
- **Item 2:**
  - Name: `Accept`
  - Value: `application/json`

**Parse response:**
```
Yes
```

### Module 3: HTTP - ส่งไป WordPress

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
  "bar_buy": "{{2.data.response.price.gold_bar.buy}}",
  "bar_sell": "{{2.data.response.price.gold_bar.sell}}",
  "jewelry_buy": "{{2.data.response.price.gold_ornament.buy}}",
  "jewelry_sell": "{{2.data.response.price.gold_ornament.sell}}",
  "change": "{{2.data.response.price.change}}"
}
```

**หมายเหตุ:** ถ้า API structure ไม่ตรง ให้ดูจาก response ที่ได้จริงแล้วปรับ path

---

## 🎯 วิธีแก้ปัญหา Scenario 2 (ราคาน้ำมัน) แบบง่าย

ถ้า Bangchak API ซับซ้อนเกินไป ให้ใช้วิธีนี้:

### Module 2: HTTP - ดึงข้อมูลจาก Bangchak

**URL:**
```
https://oil-price.bangchak.co.th/api/oilprice
```

### Module 3: JSON Parse

**JSON string:**
```
{{2.data}}
```

### Module 4: Array Aggregator

รวมข้อมูลทั้งหมดเป็น array

### Module 5: HTTP - ส่งไป WordPress

**Request content (ตัวอย่าง):**
```json
{
  "gasohol_91": {
    "today": "31.48",
    "tomorrow": "31.48"
  },
  "gasohol_95": {
    "today": "31.85",
    "tomorrow": "31.85"
  },
  "e85": {
    "today": "27.59",
    "tomorrow": "27.59"
  },
  "e20": {
    "today": "29.64",
    "tomorrow": "29.64"
  },
  "diesel_premium": {
    "today": "45.64",
    "tomorrow": "45.64"
  },
  "biodiesel": {
    "today": "30.94",
    "tomorrow": "30.94"
  },
  "gasohol_97": {
    "today": "49.54",
    "tomorrow": "49.54"
  }
}
```

---

## 🔍 ทดสอบ API ก่อนสร้าง Scenario

### ทดสอบ API ธปท.
```bash
curl "https://www.bot.or.th/content/bot/th/statistics/exchange-rate/jcr:content/root/container/statisticstable2.results.level1cache.json"
```

### ทดสอบ API Bangchak
```bash
curl "https://oil-price.bangchak.co.th/api/oilprice"
```

### ทดสอบ API ทอง
```bash
curl "https://www.goldtraders.or.th/api/goldapi/"
```

---

## ✅ สรุป

ใช้ API จริงแทนการ scrape:
1. **อัตราแลกเปลี่ยน** → ธปท. API (ใช้อยู่แล้ว)
2. **ราคาน้ำมัน** → Bangchak API
3. **ราคาทอง** → Gold Traders API

**ข้อดี:**
- ✅ ไม่โดนบลอก
- ✅ ข้อมูลเหมือน aommoney.com
- ✅ เสถียร
- ✅ ฟรี
