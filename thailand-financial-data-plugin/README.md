# Thailand Financial Data Plugin

Plugin สำหรับดึงข้อมูลทางการเงินของไทย (อัตราแลกเปลี่ยน, ราคาน้ำมัน, ราคาทอง) จาก Make.com และแสดงผ่าน Elementor Dynamic Tags

## 📦 การติดตั้ง

1. อัปโหลดโฟลเดอร์ `thailand-financial-data-plugin` ไปที่ `/wp-content/plugins/`
2. เข้า WordPress Admin → Plugins → เปิดใช้งาน "Thailand Financial Data"
3. เมนู "Financial Data" จะปรากฏในแถบด้านซ้าย

## 🔗 API Endpoints

หลังจากติดตั้งแล้ว คุณจะได้ API endpoints เหล่านี้:

### 1. อัตราแลกเปลี่ยน
- **URL:** `https://yoursite.com/wp-json/thailand-financial/v1/exchange-rates`
- **Method:** POST
- **Body (JSON):**
```json
{
  "USD": {
    "buy": "32.46",
    "sell": "32.79"
  },
  "EUR": {
    "buy": "37.65",
    "sell": "38.35"
  },
  "GBP": {
    "buy": "43.12",
    "sell": "43.94"
  }
}
```

### 2. ราคาน้ำมัน
- **URL:** `https://yoursite.com/wp-json/thailand-financial/v1/oil-prices`
- **Method:** POST
- **Body (JSON):**
```json
{
  "diesel_premium": {
    "today": "45.64",
    "tomorrow": "45.64"
  },
  "biodiesel": {
    "today": "30.94",
    "tomorrow": "30.94"
  },
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
  "gasohol_97": {
    "today": "49.54",
    "tomorrow": "49.54"
  }
}
```

### 3. ราคาทอง
- **URL:** `https://yoursite.com/wp-json/thailand-financial/v1/gold-prices`
- **Method:** POST
- **Body (JSON):**
```json
{
  "bar_buy": "60550",
  "bar_sell": "60650",
  "jewelry_buy": "60000",
  "jewelry_sell": "61150",
  "change": "+750"
}
```

## 🎯 การใช้งานใน Elementor

### Dynamic Tags

หลังจากติดตั้งแล้ว คุณจะเห็น Dynamic Tags ใหม่ใน Elementor:

1. เพิ่ม Text widget ใน Elementor
2. คลิกที่ไอคอน Dynamic Tags (รูปฐานข้อมูล)
3. เลือกจากหมวด **"thailand-financial"**:
   - **อัตราแลกเปลี่ยน** - แสดงราคาซื้อ/ขายสกุลเงิน
   - **ราคาน้ำมัน** - แสดงราคาน้ำมันวันนี้/พรุ่งนี้
   - **ราคาทอง** - แสดงราคาทองคำแท่ง/รูปพรรณ

### ตัวอย่างการใช้งาน

**แสดงราคาซื้อ USD:**
1. เพิ่ม Text widget
2. เลือก Dynamic Tag → "อัตราแลกเปลี่ยน"
3. ตั้งค่า:
   - สกุลเงิน: USD
   - ประเภท: ราคาซื้อ

**แสดงราคาแก๊สโซฮอล์ 91:**
1. เพิ่ม Text widget
2. เลือก Dynamic Tag → "ราคาน้ำมัน"
3. ตั้งค่า:
   - ชนิดน้ำมัน: แก๊สโซฮอล์ 91 S EVO
   - วัน: วันนี้

## 📝 Shortcodes

### อัตราแลกเปลี่ยน
```
[tfd_exchange_rate currency="USD" type="buy"]
```
**Parameters:**
- `currency`: USD, EUR, GBP, CAD, JPY, AUD, INR
- `type`: buy, sell

### ราคาน้ำมัน
```
[tfd_oil_price type="gasohol_91" day="today"]
```
**Parameters:**
- `type`: diesel_premium, biodiesel, gasohol_97, e85, e20, gasohol_91, gasohol_95
- `day`: today, tomorrow

### ราคาทอง
```
[tfd_gold_price type="bar_buy"]
```
**Parameters:**
- `type`: bar_buy, bar_sell, jewelry_buy, jewelry_sell, change

## 🔧 การตั้งค่า API Key (ไม่บังคับ)

ถ้าต้องการความปลอดภัย:

1. ไปที่ Financial Data → ตั้งค่า
2. ตั้ง API Key (เช่น `my-secret-key-12345`)
3. บันทึก
4. ใน Make.com ให้เพิ่ม Header:
   - Key: `X-API-Key`
   - Value: `my-secret-key-12345`

## 📊 Dashboard

ดูข้อมูลทั้งหมดได้ที่:
- WordPress Admin → Financial Data

จะแสดง:
- ข้อมูลล่าสุดทั้ง 3 ประเภท
- เวลาอัปเดตล่าสุด
- API Endpoints สำหรับใช้ใน Make.com

## 🔄 Version

**1.0.0** - เวอร์ชันแรก
