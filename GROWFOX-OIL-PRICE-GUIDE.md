# คู่มือการใช้งาน Growfox Oil Price (ราคาน้ำมัน)

## 📦 ติดตั้ง Plugin

1. อัพโหลดไฟล์ `growfox-rest-api-v3.zip` ใน WordPress
2. ไปที่ **Plugins > Add New > Upload Plugin**
3. เลือกไฟล์ ZIP และกด **Install Now**
4. กด **Activate Plugin**

---

## 🔧 ตั้งค่า Make.com

### 1. สร้าง Scenario ใหม่

1. เพิ่ม Module: **HTTP > Make a request**
2. ตั้งค่าดังนี้:

```
URL: https://synergycrafted.com/wp-json/growfox/v1/oil-prices
Method: POST
Body type: Raw
Content type: application/json
```

### 2. Request Body Format

ส่งข้อมูลในรูปแบบ Array ของ Object:

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
  }
]
```

### 3. ตัวอย่างการดึงข้อมูลจาก Bangchak API

ถ้าต้องการดึงข้อมูลจาก Bangchak:

1. เพิ่ม Module: **HTTP > Make a request**
2. URL: `https://oil-price.bangchak.co.th/api/oilprice`
3. Method: GET
4. ใช้ **JSON > Parse JSON** เพื่อแปลงข้อมูล
5. ใช้ **Array Aggregator** เพื่อรวมข้อมูล
6. Map ข้อมูลตามโครงสร้างด้านบน

---

## 📝 วิธีใช้งานใน WordPress

### 1. แสดงตารางราคาน้ำมัน

ใช้ Shortcode:

```
[growfox_oil]
```

### 2. วางใน Kubio Theme

1. ไปที่หน้าที่ต้องการแสดงตารางน้ำมัน
2. เพิ่ม **Shortcode Block**
3. ใส่ `[growfox_oil]`
4. บันทึกและดูผล

---

## 🎨 การแสดงผล

ตารางจะแสดงในรูปแบบ:

- **หัวตาราง**: ชนิดน้ำมัน (บาท/ลิตร) | วันนี้ | พรุ่งนี้
- **แต่ละแถว**: รูปภาพน้ำมัน | ราคาวันนี้ | ราคาพรุ่งนี้
- **สีเขียว**: ธีมสีเขียวตามแบบ Bangchak
- **Hover Effect**: เมื่อเลื่อนเมาส์จะมีการเปลี่ยนสี
- **Responsive**: รองรับทุกขนาดหน้าจอ

---

## 🔍 ตรวจสอบข้อมูล

### ดูข้อมูลที่บันทึกแล้ว (GET)

```
https://synergycrafted.com/wp-json/growfox/v1/oil-prices
```

Response:

```json
{
  "success": true,
  "last_update": "2025-01-14 10:30:00",
  "data": {
    "products": [
      {
        "name": "ไฮพรีเมียมดีเซล S",
        "price_today": "45.64",
        "price_tomorrow": "45.64",
        "image": "https://..."
      }
    ],
    "date": "14/01/2569"
  }
}
```

---

## ⚠️ Troubleshooting

### ปัญหา: 404 Not Found

**สาเหตร**: Plugin ยังไม่ได้ activate หรือ permalink ไม่ถูกต้อง

**แก้ไข**:
1. ตรวจสอบว่า Plugin activate แล้ว
2. ไปที่ **Settings > Permalinks**
3. กด **Save Changes** (ไม่ต้องเปลี่ยนอะไร)
4. ลองส่งข้อมูลใหม่

### ปัญหา: ไม่แสดงข้อมูล

**สาเหตร**: ยังไม่มีข้อมูลในระบบ

**แก้ไข**:
1. ส่งข้อมูลจาก Make.com ก่อน
2. ตรวจสอบว่าได้ Response 200 OK
3. Refresh หน้าเว็บ

### ปัญหา: รูปภาพไม่แสดง

**สาเหตร**: URL รูปภาพไม่ถูกต้องหรือ CORS

**แก้ไข**:
1. ตรวจสอบ `ImageUrl` ใน Request Body
2. ลองเปิด URL รูปภาพในเบราว์เซอร์
3. ถ้าไม่ได้ ให้ใช้รูปภาพจาก CDN อื่น

---

## 📊 ตัวอย่างข้อมูลครบ

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

## 🎯 สรุป

1. ติดตั้ง Plugin `growfox-rest-api-v3.zip`
2. ตั้งค่า Make.com ส่งข้อมูลไปที่ `/wp-json/growfox/v1/oil-prices`
3. ใช้ Shortcode `[growfox_oil]` ในหน้าที่ต้องการ
4. ตารางจะแสดงราคาน้ำมันแบบสวยงามตามธีม Bangchak

---

**หมายเหตุ**: Plugin นี้รองรับทั้ง Exchange Rates, Gold Prices และ Oil Prices ในตัวเดียว
