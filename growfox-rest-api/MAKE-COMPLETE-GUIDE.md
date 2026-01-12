# คู่มือตั้งค่า Make.com แบบละเอียด (สำหรับ AI Agent)

## 🎯 เป้าหมาย
สร้าง Scenario ใน Make.com ที่ดึงอัตราแลกเปลี่ยน 48 สกุลเงินจาก ธนาคารแห่งประเทศไทย (BoT) และส่งไปยัง WordPress

---

## 📋 ข้อมูลที่ต้องเตรียม

1. **WordPress Site URL:** `https://yourdomain.com`
2. **API Endpoint:** `https://yourdomain.com/wp-json/growfox/v1/exchange-rates`
3. **BoT API URL:** `https://www.bot.or.th/content/bot/th/statistics/exchange-rate/jcr:content/root/container/statisticstable2.results.level1cache.json`

---

## 🔧 ขั้นตอนการสร้าง Scenario

### **Step 1: สร้าง Scenario ใหม่**

1. เข้า Make.com Dashboard
2. คลิก **"Create a new scenario"**
3. ตั้งชื่อ: `"BoT Exchange Rates to WordPress"`

---

### **Step 2: เพิ่ม Module 1 - HTTP Request (Get BoT API)**

#### **2.1 เลือก Module:**
- คลิก **"+"** เพื่อเพิ่ม Module
- ค้นหา: **"HTTP"**
- เลือก: **"HTTP > Make a request"**

#### **2.2 ตั้งค่า HTTP Request:**

**URL:**
```
https://www.bot.or.th/content/bot/th/statistics/exchange-rate/jcr:content/root/container/statisticstable2.results.level1cache.json
```

**Method:**
```
GET
```

**Headers:**
```json
{
  "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
  "Accept": "application/json"
}
```

**Parse response:**
```
Yes
```

**Timeout:**
```
45
```

#### **2.3 คลิก "OK" เพื่อบันทึก**

---

### **Step 3: เพิ่ม Module 2 - HTTP Request (Send to WordPress)**

#### **3.1 เลือก Module:**
- คลิก **"+"** หลัง Module 1
- ค้นหา: **"HTTP"**
- เลือก: **"HTTP > Make a request"**

#### **3.2 ตั้งค่า HTTP Request:**

**URL:**
```
https://yourdomain.com/wp-json/growfox/v1/exchange-rates
```
*(เปลี่ยน `yourdomain.com` เป็นโดเมนจริง)*

**Method:**
```
POST
```

**Headers:**
```json
{
  "Content-Type": "application/json"
}
```

**Body type:**
```
Raw
```

**Request content:**
```
{{1.data.responseContent}}
```

**Parse response:**
```
Yes
```

#### **3.3 คลิก "OK" เพื่อบันทึก**

---

### **Step 4: ตั้งค่า Scheduling**

#### **4.1 คลิกที่นาฬิกา (Clock icon) ที่ Module แรก**

#### **4.2 เลือก Schedule:**
```
Every 6 hours
```

**หรือเลือกตามต้องการ:**
- Every 3 hours
- Every 12 hours
- Once a day (at 11:00 AM)

#### **4.3 ตั้งเวลาเริ่มต้น:**
```
Start: Today
Time: 11:00 AM
```

---

### **Step 5: ทดสอบ Scenario**

#### **5.1 คลิก "Run once"**

#### **5.2 ตรวจสอบผลลัพธ์:**

**Module 1 Output ควรเห็น:**
```json
{
  "data": {
    "responseContent": [
      {
        "currency_id": "USD",
        "currency_name_th": "ดอลลาร์สหรัฐ",
        "buying_transfer": "34.50",
        "selling": "35.20",
        "period": "2025-10-28"
      },
      ...
    ]
  }
}
```

**Module 2 Output ควรเห็น:**
```json
{
  "data": {
    "success": true,
    "message": "Updated 48 currencies",
    "count": 48
  }
}
```

#### **5.3 ถ้าสำเร็จ:**
- Status: ✅ Success (สีเขียว)
- คลิก **"Turn on"** เพื่อเปิดใช้งาน

#### **5.4 ถ้าเกิด Error:**

**Error: "Connection timeout"**
- แก้: เพิ่ม Timeout เป็น 60 วินาที

**Error: "Invalid JSON"**
- แก้: ตรวจสอบ URL BoT API ว่าถูกต้อง

**Error: "404 Not Found" (WordPress)**
- แก้: ตรวจสอบว่า Plugin ติดตั้งและ Activate แล้ว

---

## 📊 โครงสร้างข้อมูลที่ส่ง

### **ข้อมูลจาก BoT API (Module 1):**
```json
{
  "responseContent": [
    {
      "currency_id": "USD",
      "currency_name_th": "ดอลลาร์สหรัฐ",
      "currency_name_eng": "UNITED STATES : DOLLAR (USD)",
      "buying_sight": "34.30",
      "buying_transfer": "34.50",
      "selling": "35.20",
      "flagPath": "/content/dam/bot/currency/flags/USD.png",
      "period": "2025-10-28",
      "sortOrder": 1
    },
    {
      "currency_id": "EUR",
      "currency_name_th": "ยูโร",
      "currency_name_eng": "EURO ZONE : EURO (EUR)",
      "buying_sight": "38.10",
      "buying_transfer": "38.30",
      "selling": "39.50",
      "flagPath": "/content/dam/bot/currency/flags/EUR.png",
      "period": "2025-10-28",
      "sortOrder": 2
    }
    // ... อีก 46 สกุล
  ]
}
```

### **ข้อมูลที่ส่งไป WordPress (Module 2):**
```json
[
  {
    "currency_id": "USD",
    "currency_name_th": "ดอลลาร์สหรัฐ",
    "currency_name_eng": "UNITED STATES : DOLLAR (USD)",
    "buying_sight": "34.30",
    "buying_transfer": "34.50",
    "selling": "35.20",
    "period": "2025-10-28"
  },
  {
    "currency_id": "EUR",
    "currency_name_th": "ยูโร",
    "currency_name_eng": "EURO ZONE : EURO (EUR)",
    "buying_sight": "38.10",
    "buying_transfer": "38.30",
    "selling": "39.50",
    "period": "2025-10-28"
  }
  // ... อีก 46 สกุล
]
```

---

## ✅ Checklist การตั้งค่า

- [ ] สร้าง Scenario ใหม่
- [ ] เพิ่ม Module 1: HTTP GET (BoT API)
  - [ ] URL ถูกต้อง
  - [ ] Headers ครบ
  - [ ] Parse response = Yes
- [ ] เพิ่ม Module 2: HTTP POST (WordPress)
  - [ ] URL ถูกต้อง (เปลี่ยนโดเมน)
  - [ ] Method = POST
  - [ ] Body = `{{1.data.responseContent}}`
- [ ] ตั้งค่า Schedule (Every 6 hours)
- [ ] ทดสอบด้วย "Run once"
- [ ] ตรวจสอบผลลัพธ์ใน WordPress
- [ ] เปิดใช้งาน Scenario

---

## 🔍 การตรวจสอบใน WordPress

### **1. ตรวจสอบข้อมูลใน ACF:**
1. เข้า WordPress Admin
2. ไปที่ **"อัตราแลกเปลี่ยน"** (เมนูด้านซ้าย)
3. ดูข้อมูลใน Field "ข้อมูลอัตราแลกเปลี่ยน (JSON)"
4. ควรเห็น JSON ของสกุลเงินทั้งหมด

### **2. ทดสอบใน Elementor:**
1. สร้าง Page ใหม่
2. Edit with Elementor
3. ลาก Heading Widget
4. คลิก Dynamic Tag → Growfox → อัตราแลกเปลี่ยน
5. เลือกสกุลเงิน: USD
6. เลือกข้อมูล: อัตราขาย
7. ควรเห็นตัวเลข เช่น "35.2000"

---

## 🚨 Troubleshooting

### **ปัญหา: Dropdown ใน Elementor ไม่มีสกุลเงิน**
**สาเหตุ:** ข้อมูลยังไม่ถูกส่งจาก Make.com
**แก้ไข:**
1. รัน Scenario ใน Make.com ด้วย "Run once"
2. Refresh Elementor Editor
3. ลอง Dynamic Tag อีกครั้ง

### **ปัญหา: แสดงผล "--" แทนตัวเลข**
**สาเหตุ:** สกุลเงินที่เลือกไม่มีในข้อมูล
**แก้ไข:**
1. ตรวจสอบว่า Make.com ส่งข้อมูลสำเร็จ
2. ตรวจสอบ Field "ข้อมูลอัตราแลกเปลี่ยน (JSON)" ใน ACF
3. ตรวจสอบว่าสกุลเงินที่เลือกมีใน JSON

### **ปัญหา: Make.com Error "Invalid JSON"**
**สาเหตุ:** BoT API เปลี่ยน URL หรือโครงสร้างข้อมูล
**แก้ไข:**
1. ทดสอบ URL ใน Browser
2. ตรวจสอบว่าได้ JSON กลับมา
3. อัปเดต URL ใน Module 1

---

## 📞 Support

หากมีปัญหา ให้ตรวจสอบ:
1. Plugin ติดตั้งและ Activate แล้ว
2. ACF Plugin ติดตั้งแล้ว
3. Make.com Scenario เปิดใช้งานแล้ว
4. URL WordPress ถูกต้อง

---

## 🎉 เสร็จสิ้น!

หลังจากตั้งค่าเสร็จ:
- Make.com จะดึงข้อมูลอัตโนมัติทุก 6 ชั่วโมง
- WordPress จะมีข้อมูลอัตราแลกเปลี่ยนล่าสุดเสมอ
- ใช้ Dynamic Tags ใน Elementor ได้ทันที
