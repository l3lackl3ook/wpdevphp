# วิธีตั้งค่า Make.com สำหรับราคาน้ำมัน

## 🎯 เป้าหมาย

ส่งข้อมูลราคาน้ำมันจาก Make.com ไปยัง WordPress API

---

## 📋 ข้อมูลที่ต้องเตรียม

- **URL**: `https://synergycrafted.com/wp-json/growfox/v1/oil-prices`
- **Method**: POST
- **Content-Type**: application/json

---

## 🔧 ขั้นตอนการตั้งค่า Make.com

### 1. สร้าง Scenario ใหม่

1. เข้า Make.com
2. คลิก **Create a new scenario**
3. ตั้งชื่อ: "Bangchak Oil Prices to WordPress"

### 2. เพิ่ม Module แรก: HTTP Request (ดึงข้อมูลจาก Bangchak)

**ถ้าต้องการดึงจาก Bangchak API:**

```
Module: HTTP > Make a request
URL: https://oil-price.bangchak.co.th/api/oilprice
Method: GET
```

**หรือใช้ข้อมูลจาก Google Sheets / Airtable / Manual**

### 3. เพิ่ม Module: Array Aggregator (รวมข้อมูล)

```
Module: Tools > Array aggregator
Source Module: [Module ก่อนหน้า]
```

**Map ข้อมูลดังนี้:**

| Field | Value |
|-------|-------|
| OilName | ชื่อน้ำมัน (เช่น "ไฮพรีเมียมดีเซล S") |
| PriceToday | ราคาวันนี้ (เช่น "45.64") |
| PriceTomorrow | ราคาพรุ่งนี้ (เช่น "45.64") |
| ImageUrl | URL รูปภาพ (เช่น "https://...") |

### 4. เพิ่ม Module: HTTP Request (ส่งไป WordPress)

```
Module: HTTP > Make a request
URL: https://synergycrafted.com/wp-json/growfox/v1/oil-prices
Method: POST
Body type: Raw
Content type: application/json
```

**Request Body:**

ใช้ output จาก Array Aggregator:

```
{{array}}
```

หรือถ้าต้องการใส่ข้อมูลเอง:

```json
[
  {
    "OilName": "ไฮพรีเมียมดีเซล S",
    "PriceToday": "45.64",
    "PriceTomorrow": "45.64",
    "ImageUrl": "https://webbcpopaprd001.azurewebsites.net/ApiGetImages?FileName=133589693386477004.jpg"
  },
  {
    "OilName": "ไฮดีเซล S",
    "PriceToday": "29.94",
    "PriceTomorrow": "29.94",
    "ImageUrl": "https://webbcpopaprd001.azurewebsites.net/ApiGetImages?FileName=133589692550720467.jpg"
  }
]
```

---

## ✅ ตัวอย่าง Request Body ที่ถูกต้อง

```json
[
  {
    "OilName": "ไฮพรีเมียมดีเซล S",
    "PriceToday": "45.64",
    "PriceTomorrow": "45.64",
    "ImageUrl": "https://webbcpopaprd001.azurewebsites.net/ApiGetImages?FileName=133589693386477004.jpg"
  },
  {
    "OilName": "ไฮดีเซล S",
    "PriceToday": "29.94",
    "PriceTomorrow": "29.94",
    "ImageUrl": "https://webbcpopaprd001.azurewebsites.net/ApiGetImages?FileName=133589692550720467.jpg"
  },
  {
    "OilName": "ไฮพรีเมียม 97 แก๊สโซฮอล์ 95",
    "PriceToday": "49.54",
    "PriceTomorrow": "49.54",
    "ImageUrl": "https://webbcpopaprd001.azurewebsites.net/ApiGetImages?FileName=133619601660983407.jpg"
  },
  {
    "OilName": "แก๊สโซฮอล์ E85 S EVO",
    "PriceToday": "26.59",
    "PriceTomorrow": "26.59",
    "ImageUrl": "https://webbcpopaprd001.azurewebsites.net/ApiGetImages?FileName=E85evoMobile176.jpg"
  },
  {
    "OilName": "แก๊สโซฮอล์ E20 S EVO",
    "PriceToday": "28.64",
    "PriceTomorrow": "28.64",
    "ImageUrl": "https://webbcpopaprd001.azurewebsites.net/ApiGetImages?FileName=E20evoMobile176.jpg"
  },
  {
    "OilName": "แก๊สโซฮอล์ 91 S EVO",
    "PriceToday": "30.48",
    "PriceTomorrow": "30.48",
    "ImageUrl": "https://webbcpopaprd001.azurewebsites.net/ApiGetImages?FileName=GSH91evoMobile176.jpg"
  },
  {
    "OilName": "แก๊สโซฮอล์ 95 S EVO",
    "PriceToday": "30.85",
    "PriceTomorrow": "30.85",
    "ImageUrl": "https://webbcpopaprd001.azurewebsites.net/ApiGetImages?FileName=GSH95evoMobile176.jpg"
  }
]
```

---

## 🔍 ตรวจสอบผลลัพธ์

### Response ที่ถูกต้อง (200 OK):

```json
{
  "success": true,
  "message": "Oil prices updated",
  "count": 7,
  "timestamp": "2025-01-14 10:30:00"
}
```

### Response ที่ผิดพลาด:

**404 Not Found:**
- Plugin ยังไม่ได้ activate
- URL ผิด
- Permalink ไม่ถูกต้อง

**400 Bad Request:**
- Request Body ไม่ถูกต้อง
- ไม่ใช่ JSON Array
- ขาดข้อมูลที่จำเป็น

---

## ⏰ ตั้งเวลาอัพเดทอัตโนมัติ

1. คลิกที่ **Clock icon** ใน Scenario
2. เลือก **Schedule**
3. ตั้งเวลา: ทุกวันเวลา 09:00 น.
4. หรือทุก 1 ชั่วโมง

---

## 🎨 แสดงผลใน WordPress

ใช้ Shortcode:

```
[growfox_oil]
```

วางใน:
- Kubio Shortcode Block
- Gutenberg Shortcode Block
- Text Widget
- หรือใน PHP: `<?php echo do_shortcode('[growfox_oil]'); ?>`

---

## ⚠️ หมายเหตุสำคัญ

1. **ต้องส่งเป็น Array**: `[{...}, {...}]` ไม่ใช่ Object `{...}`
2. **ต้องมี 4 fields**: OilName, PriceToday, PriceTomorrow, ImageUrl
3. **ราคาเป็น String**: "45.64" ไม่ใช่ 45.64
4. **ImageUrl ต้องเป็น URL เต็ม**: เริ่มด้วย https://

---

## 🐛 Debug

ดูข้อมูลที่บันทึกแล้ว:

```
GET https://synergycrafted.com/wp-json/growfox/v1/oil-prices
```

ดู WordPress Error Log:

```
wp-content/debug.log
```

---

**สำเร็จ!** ตอนนี้ระบบพร้อมใช้งานแล้ว 🎉
